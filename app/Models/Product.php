<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Les champs modifiables en masse
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'image_path',
        'video_path',
        'video_url',
        'status',
        'transaction_type',
        'trade_wishlist',
        'city',
        'quantity',
        'is_available',
        'sold_out_at',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'sold_out_at' => 'datetime',
    ];

    /**
     * Relation : Un produit appartient à un utilisateur (le vendeur)
     */
    /**
     * Les utilisateurs qui ont mis ce produit en favori.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Scope visibilité publique: disponible, ou épuisé depuis < 24h
     */
    public function scopeAvailable($query)
    {
        $threshold = now()->subDay();
        return $query->where(function ($q) use ($threshold) {
            $q->where('is_available', true)
              ->orWhere(function ($q2) use ($threshold) {
                  $q2->where('quantity', 0)
                     ->whereNotNull('sold_out_at')
                     ->where('sold_out_at', '>=', $threshold);
              });
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un produit appartient à une catégorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function restockSubscriptions()
    {
        return $this->hasMany(\App\Models\RestockSubscription::class);
    }
}
