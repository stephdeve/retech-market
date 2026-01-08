<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkout()
    {
        // Récupère le panier depuis la session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        // Calcule le total du panier
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'];
        }

        // Affiche la page de paiement avec le récapitulatif
        return view('payment.checkout', compact('cart', 'total'));
    }

    public function callback(Request $request)
    {
        $transactionId = $request->query('transaction_id');

        // Verify transaction with Kkiapay API (Optional but recommended)
        // For now, we assume success if transaction_id is present as per simulation request
        
        if (!$transactionId) {
            return redirect()->route('cart.index')->with('error', 'Paiement échoué ou annulé.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('dashboard')->with('error', 'Panier vide après paiement.');
        }

        try {
            $orders = collect();

            DB::transaction(function () use ($cart, &$orders) {
                foreach ($cart as $id => $details) {
                    // Lock the product row where supported (ignored on SQLite)
                    $product = Product::where('id', $id)->lockForUpdate()->first();
                    if (!$product) {
                        throw new \RuntimeException('Produit introuvable.');
                    }

                    // Check availability
                    if ($product->quantity <= 0 || $product->is_available === false) {
                        throw new \RuntimeException('Produit indisponible: ' . $product->name);
                    }

                    // Create Order
                    $order = Order::create([
                        'buyer_id' => auth()->id(),
                        'product_id' => $product->id,
                        'total_price' => $product->price,
                        'status' => 'Paid',
                    ]);

                    // Decrement stock
                    $product->quantity = max(0, (int)$product->quantity - 1);

                    // If hit zero, mark unavailable and timestamp
                    if ($product->quantity === 0) {
                        $product->is_available = false;
                        $product->sold_out_at = now();
                        // Keep legacy status update only when sold out
                        $product->status = 'Vendu';
                    } else {
                        $product->is_available = true;
                        // legacy status remains 'En Vente'
                        if ($product->status !== 'En Vente') {
                            $product->status = 'En Vente';
                        }
                    }
                    $product->save();

                    // Alerts to seller
                    try {
                        if ($product->quantity === 1) {
                            $product->user->notify(new \App\Notifications\LowStockNotification($product));
                        }
                        if ($product->quantity === 0) {
                            $product->user->notify(new \App\Notifications\OutOfStockNotification($product));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Stock Alert Notification Error: ' . $e->getMessage());
                    }

                    // Seller sale notification (existing)
                    try {
                        \Illuminate\Support\Facades\Mail::to($product->user)->send(new \App\Mail\SaleNotification($order));
                    } catch (\Exception $e) {
                        Log::error('Mail Error (Seller): ' . $e->getMessage());
                    }

                    try {
                        $product->user->notify(new \App\Notifications\ProductSoldNotification($order));
                    } catch (\Exception $e) {
                        Log::error('Notification Error (Seller): ' . $e->getMessage());
                    }

                    // Add to orders collection
                    $orders->push($order);
                }
            });

            // Send Confirmation to Buyer
            if ($orders->isNotEmpty()) {
                $total = $orders->sum('total_price');
                try {
                    \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\OrderConfirmationNotification($orders, $total));
                } catch (\Exception $e) {
                    Log::error('Mail Error (Buyer): ' . $e->getMessage());
                }

                // Send In-App & Real-time Notification to Buyer
                try {
                    auth()->user()->notify(new \App\Notifications\OrderPlacedNotification($total, $orders->count()));
                } catch (\Exception $e) {
                    Log::error('Notification Error (Buyer): ' . $e->getMessage());
                }
            }

            // Clear Cart
            session()->forget('cart');

            // Persist a summary of this payment for the success page
            session()->put('last_payment_order_ids', $orders->pluck('id')->all());
            session()->put('last_payment_total', $orders->sum('total_price'));

            // Redirect to success page with recap
            return redirect()->route('payment.success')->with('success', 'Paiement réussi ! Vos commandes ont été créées et les vendeurs notifiés.');

        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Une erreur est survenue lors de la création de la commande.');
        }
    }

    /**
     * Affiche la page de succès après paiement avec le récapitulatif
     */
    public function success(Request $request)
    {
        // Retrieve and consume last payment data from session
        $orderIds = session()->pull('last_payment_order_ids', []);
        $total = session()->pull('last_payment_total', null);

        if (empty($orderIds)) {
            // Fallback if page is refreshed or session expired
            return redirect()->route('purchases')->with('info', 'Aucune commande récente à afficher.');
        }

        // Load created orders with relations
        $orders = Order::with(['product.user', 'buyer'])->whereIn('id', $orderIds)->get();

        return view('payment.success', compact('orders', 'total'));
    }

    /**
     * Annule une commande et restaure le stock de manière atomique.
     */
    public function cancel(Request $request, \App\Models\Order $order)
    {
        // Autorisation basique: acheteur ou vendeur du produit
        if (!in_array(auth()->id(), [$order->buyer_id, optional($order->product)->user_id])) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($order) {
                // Ne pas retraiter si déjà annulée / remboursée
                if (in_array($order->status, ['Cancelled', 'Refunded'])) {
                    return;
                }

                // Verrouille la ligne produit (si supporté)
                $product = Product::where('id', $order->product_id)->lockForUpdate()->first();
                if ($product) {
                    $product->quantity = (int) $product->quantity + 1;
                    $product->is_available = true;
                    $product->sold_out_at = null;
                    if ($product->status !== 'En Vente') {
                        $product->status = 'En Vente';
                    }
                    $product->save();
                }

                $order->status = 'Cancelled';
                $order->save();
            });

            return back()->with('success', 'Commande annulée et stock restauré.');
        } catch (\Throwable $e) {
            \Log::error('Cancel order error: ' . $e->getMessage());
            return back()->with('error', 'Impossible d\'annuler la commande pour le moment.');
        }
    }
}
