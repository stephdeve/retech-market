<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/**
 * 🌍 Routes Publiques (accessibles à tous)
 */
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->where('product', '[0-9]+');

// Catégories publiques
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

/**
 * 🔒 Routes Authentifiées (nécessite connexion)
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Tableau de bord : Mes annonces
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    
    // Favoris
    Route::get('/favorites', function () {
        $favorites = auth()->user()->favorites()->with('category')->latest('favorites.created_at')->get();
        return view('products.favorites', compact('favorites'));
    })->name('favorites');

    // Administration
    Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/{user}/promote', [AdminController::class, 'promoteToAdmin'])->name('users.promote');
        Route::post('/users/{user}/demote', [AdminController::class, 'demoteFromAdmin'])->name('users.demote');
    });

    // Achats & Ventes
    Route::post('/products/{product}/buy', [ProductController::class, 'buy'])->name('products.buy');
    Route::get('/purchases', [ProductController::class, 'purchases'])->name('purchases');
    // Route unique pour les ventes (suppression du doublon)
    Route::get('/sales', [ProductController::class, 'sales'])->name('sales');
    // Abonnement au retour en stock
    Route::post('/products/{product}/restock-subscribe', [ProductController::class, 'subscribeRestock'])->name('products.restock-subscribe');
    
    // Panier
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Paiement
    Route::get('/payment/checkout', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationsController::class, 'readAll'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationsController::class, 'delete'])->name('notifications.delete');

    // Commandes & Chat
    Route::get('/orders/{order}', function (App\Models\Order $order) {
        // Authorization check
        if (auth()->id() !== $order->buyer_id && auth()->id() !== $order->product->user_id) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    })->name('orders.show');
    // Annulation d'une commande (restaure le stock)
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('orders.cancel');

    // Messagerie complète (par produit et utilisateur)
    Route::get('/messages', [App\Http\Controllers\ConversationController::class, 'index'])->name('messages.index');
    Route::get('/messages/{product}/{user}', [App\Http\Controllers\ConversationController::class, 'show'])->name('messages.show');
    Route::post('/messages/{product}/{user}', [App\Http\Controllers\ConversationController::class, 'send'])->name('messages.send');

    // Gestion des catégories (utilisateurs authentifiés)
    Route::get('/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('role:admin');

    // CRUD complet des produits (sauf index et show déjà publics)
    Route::resource('products', ProductController::class)->except(['index', 'show']);
});
