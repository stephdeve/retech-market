<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * ✅ Tout utilisateur authentifié peut voir la liste
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * ✅ Tout le monde peut voir un produit spécifique (même non connecté)
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * ✅ Tout utilisateur connecté peut créer un produit
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * 🔒 Seul le propriétaire peut modifier son produit
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    /**
     * 🔒 Seul le propriétaire peut supprimer son produit
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    /**
     * Restore non utilisé ici
     */
    public function restore(User $user, Product $product): bool
    {
        return $this->delete($user, $product);
    }

    /**
     * ForceDelete non utilisé ici
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $this->delete($user, $product);
    }
}
