<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * 📋 Liste publique des produits (page d'accueil)
     */
    public function index(Request $request)
    {
        // Récupère les produits visibles (disponibles ou épuisés < 24h) avec leurs relations
        $query = Product::available()->with(['user', 'category'])->latest();

        // Filtre par catégorie si présent dans l'URL
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Recherche par nom si présent
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        
        // Cache des catégories pour 24 heures (86400 secondes)
        $categories = \Illuminate\Support\Facades\Cache::remember('categories', 86400, function () {
            return Category::all();
        });

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * 👤 Tableau de bord : Mes annonces
     */
    public function dashboard()
    {
        $products = auth()->user()->products()->with('category')->latest()->get();

        // Chart Data: Product Status Distribution
        $statusCounts = $products->groupBy('status')->map->count();
        $statusLabels = $statusCounts->keys();
        $statusData = $statusCounts->values();

        // Statistiques de ventes du vendeur (total des ventes et nombre de ventes)
        // Utilise la relation `sales()` pour agréger les commandes payées liées à ses produits
        $salesCount = auth()->user()->sales()->where('orders.status', 'Paid')->count();
        $salesTotal = auth()->user()->sales()->where('orders.status', 'Paid')->sum('orders.total_price');

        return view('products.dashboard', compact('products', 'statusLabels', 'statusData', 'salesCount', 'salesTotal'));
    }

    /**
     * ➕ Affiche le formulaire de création
     */
    public function create()
    {
        $categories = \Illuminate\Support\Facades\Cache::remember('categories', 86400, function () {
            return Category::all();
        });
        return view('products.create', compact('categories'));
    }

    /**
     * 💾 Enregistre un nouveau produit
     */
    public function store(StoreProductRequest $request)
    {
        // Validation automatique via StoreProductRequest
        $validated = $request->validated();

        // Si troc uniquement et prix non fourni, on force à 0 pour respecter le schéma DB
        if (($validated['transaction_type'] ?? 'sale') === 'trade' && empty($validated['price'])) {
            $validated['price'] = 0;
        }
        // Valeur par défaut sécurisée
        $validated['transaction_type'] = $validated['transaction_type'] ?? 'sale';

        // Upload de l'image si présente
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        // Upload de la vidéo si présente
        if ($request->hasFile('video')) {
            $validated['video_path'] = $request->file('video')->store('products/videos', 'public');
        }

        // Association automatique de l'utilisateur connecté
        $validated['user_id'] = auth()->id();

        if (isset($validated['quantity']) && (int) $validated['quantity'] <= 0) {
            $validated['is_available'] = false;
            $validated['sold_out_at'] = now();
            $validated['status'] = 'Vendu';
        } else {
            $validated['is_available'] = true;
            $validated['sold_out_at'] = null;
            $validated['status'] = 'En Vente';
        }

        $product = Product::create($validated);

        // Envoi de l'email de confirmation
        \Illuminate\Support\Facades\Mail::to($request->user())->send(new \App\Mail\ProductPostedNotification($product));

        return redirect()->route('dashboard')
            ->with('success', 'Produit ajouté avec succès !');
    }

    /**
     * 👁️ Affiche un produit spécifique
     */
    public function show(Product $product)
    {
        $product->load(['user', 'category']);
        return view('products.show', compact('product'));
    }

    /**
     * ✏️ Affiche le formulaire d'édition
     */
    public function edit(Product $product)
    {
        // Vérifie que l'utilisateur est bien le propriétaire (via Policy)
        Gate::authorize('update', $product);

        $categories = \Illuminate\Support\Facades\Cache::remember('categories', 86400, function () {
            return Category::all();
        });
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * 🔄 Met à jour un produit existant
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        // Vérifie que l'utilisateur est bien le propriétaire (via Policy)
        Gate::authorize('update', $product);

        $validated = $request->validated();

        // Si troc uniquement et prix non fourni, on force à 0 pour respecter le schéma DB
        if (($validated['transaction_type'] ?? 'sale') === 'trade' && empty($validated['price'])) {
            $validated['price'] = 0;
        }
        // Valeur par défaut sécurisée
        $validated['transaction_type'] = $validated['transaction_type'] ?? 'sale';

        // Si une nouvelle image est uploadée
        if ($request->hasFile('image')) {
            // Supprime l'ancienne image (avec gestion d'erreur)
            if ($product->image_path) {
                try {
                    Storage::disk('public')->delete($product->image_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old product image: ' . $e->getMessage());
                }
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        // Si une nouvelle vidéo est uploadée
        if ($request->hasFile('video')) {
            // Supprime l'ancienne vidéo (avec gestion d'erreur)
            if ($product->video_path) {
                try {
                    Storage::disk('public')->delete($product->video_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old product video: ' . $e->getMessage());
                }
            }
            $validated['video_path'] = $request->file('video')->store('products/videos', 'public');
        }

        if (isset($validated['quantity']) && (int) $validated['quantity'] <= 0) {
            $validated['is_available'] = false;
            $validated['sold_out_at'] = now();
            $validated['status'] = 'Vendu';
        } else {
            $validated['is_available'] = true;
            $validated['sold_out_at'] = null;
            $validated['status'] = 'En Vente';
        }

        $product->update($validated);
        // Envoi de l'email de confirmation
        \Illuminate\Support\Facades\Mail::to($request->user())->send(new \App\Mail\ProductPostedNotification($product));

        return redirect()->route('dashboard')
            ->with('success', 'Produit modifié avec succès !');
    }

    /**
     * 🗑️ Supprime un produit
     */
    public function destroy(Product $product)
    {
        // Authorization
        $this->authorize('delete', $product);

        // Suppression des fichiers (avec gestion d'erreur)
        if ($product->image_path) {
            try {
                Storage::disk('public')->delete($product->image_path);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete product image: ' . $e->getMessage());
            }
        }

        if ($product->video_path) {
            try {
                Storage::disk('public')->delete($product->video_path);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete product video: ' . $e->getMessage());
            }
        }

        $product->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Produit supprimé avec succès !');
    }

    /**
     * Process the purchase of a product.
     */
    public function buy(Product $product)
    {
        // Empêche d'acheter son propre produit
        if (auth()->id() === $product->user_id) {
            return back()->with('error', 'Vous ne pouvez pas acheter votre propre produit.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($product) {
                // Verrouillage et lecture transactionnelle de la ligne produit
                $p = \App\Models\Product::where('id', $product->id)->lockForUpdate()->first();
                if (!$p) {
                    throw new \RuntimeException('Produit introuvable.');
                }

                if ((int)$p->quantity <= 0 || $p->is_available === false) {
                    throw new \RuntimeException('Ce produit n\'est plus disponible.');
                }

                // Crée la commande (paiement immédiat simulé)
                $order = \App\Models\Order::create([
                    'buyer_id' => auth()->id(),
                    'product_id' => $p->id,
                    'total_price' => $p->price,
                    'status' => 'Paid',
                ]);

                // Décrément du stock
                $p->quantity = max(0, (int)$p->quantity - 1);

                if ($p->quantity === 0) {
                    $p->is_available = false;
                    $p->sold_out_at = now();
                    $p->status = 'Vendu';
                } else {
                    $p->is_available = true;
                    if ($p->status !== 'En Vente') {
                        $p->status = 'En Vente';
                    }
                }
                $p->save();

                // Notifications stock au vendeur
                try {
                    if ($p->quantity === 1) {
                        $p->user->notify(new \App\Notifications\LowStockNotification($p));
                    }
                    if ($p->quantity === 0) {
                        $p->user->notify(new \App\Notifications\OutOfStockNotification($p));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Stock alert notify failed: ' . $e->getMessage());
                }

                // Notifications de vente (vendeur)
                try {
                    \Illuminate\Support\Facades\Mail::to($p->user)->send(new \App\Mail\SaleNotification($order));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Mail Error (Seller): ' . $e->getMessage());
                }
                try {
                    $p->user->notify(new \App\Notifications\ProductSoldNotification($order));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Notification Error (Seller): ' . $e->getMessage());
                }
            });

            return redirect()->route('purchases')->with('success', 'Félicitations ! Votre achat a été confirmé.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue lors de la transaction.');
        }
    }

    /**
     * Display the user's purchases.
     */
    public function purchases()
    {
        $orders = auth()->user()->purchases()->with('product')->latest()->get();
        return view('products.purchases', compact('orders'));
    }

    /**
     * Display the user's sales.
     */
    public function sales()
    {
        $orders = auth()->user()->sales()->with(['product', 'buyer'])->latest()->get();
        return view('products.sales', compact('orders'));
    }

    /**
     * Enregistre une demande d'alerte de retour en stock pour un produit.
     */
    public function subscribeRestock(Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            \App\Models\RestockSubscription::firstOrCreate([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
            ]);
            return back()->with('success', '✅ Vous serez prévenu(e) lorsque le produit sera de nouveau en stock.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Impossible d\'enregistrer votre demande pour le moment.');
        }
    }
}
