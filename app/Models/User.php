<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'currency',
        'phone',
        'video_path',
        'video_url',
        'social_links',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Numéro masqué pour affichage public (ex: +229 97 ** ** **)
     */
    public function getMaskedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }
        $prefix = mb_substr($this->phone, 0, 7);
        return trim($prefix) . ' ** ** **';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'social_links' => 'array',
        ];
    }

    /**
     * Get the default values for attributes.
     *
     * @return array<string, mixed>
     */
    protected $attributes = [
        'currency' => 'XOF',
    ];

    /**
     * Relation : Un utilisateur a plusieurs produits
     */
    /**
     * Les produits mis en favoris par l'utilisateur.
     */
    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Les achats effectués par l'utilisateur.
     */
    public function purchases()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Les ventes réalisées par l'utilisateur (via ses produits).
     */
    public function sales()
    {
        return $this->hasManyThrough(Order::class, Product::class, 'user_id', 'product_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
