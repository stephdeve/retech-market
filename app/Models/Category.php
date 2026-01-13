<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Les champs modifiables en masse
     */
    protected $fillable = ['name', 'slug', 'user_id'];

    /**
     * Boot method pour auto-générer le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });
    }

    /**
     * Relation : Une catégorie appartient à un utilisateur (optionnel)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Une catégorie a plusieurs produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
