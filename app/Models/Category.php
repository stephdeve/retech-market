<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Les champs modifiables en masse
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Relation : Une catégorie a plusieurs produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
