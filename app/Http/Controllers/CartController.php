<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'];
        }
        return view('cart.index', ['cartItems' => $cart, 'total' => $total]);
    }

    public function add(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            return redirect()->back()->with('success', 'Produit déjà dans le panier !');
        }

        $cart[$product->id] = [
            "name" => $product->name,
            "price" => $product->price,
            "image" => $product->image_path,
            "seller" => $product->user->name
        ];

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produit retiré du panier !');
    }
}
