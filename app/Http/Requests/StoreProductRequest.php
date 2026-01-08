<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Autoriser tous les utilisateurs connectés
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Règles de validation strictes
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            // Prix requis sauf pour le troc pur
            'price' => 'required_unless:transaction_type,trade|nullable|numeric|min:0|max:9999999.99',
            // Type de transaction
            'transaction_type' => 'required|in:sale,trade,both',
            // Souhait de troc
            'trade_wishlist' => 'nullable|string|max:255',
            // Localisation
            'city' => 'required|string|max:255',
            // Stock
            'quantity' => 'required|integer|min:0|max:999999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            // Vidéo showcase
            'video' => 'nullable|file|mimes:mp4,mov|max:15360', // Max 15MB
            'video_url' => 'nullable|url|max:500',
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Veuillez sélectionner une catégorie.',
            'category_id.exists' => 'Cette catégorie n\'existe pas.',
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 20 caractères.',
            'price.required_unless' => 'Le prix est requis sauf pour une annonce en troc uniquement.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'price.max' => 'Le prix est trop élevé.',
            'transaction_type.required' => 'Veuillez choisir le type de transaction.',
            'transaction_type.in' => 'Type de transaction invalide.',
            'trade_wishlist.max' => 'Le souhait de troc est trop long (max 255).',
            'city.required' => 'La ville est obligatoire.',
            'city.max' => 'La ville est trop longue (max 255).',
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un entier.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'quantity.max' => 'La quantité est trop élevée.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format: jpeg, png, jpg ou webp.',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo.',
            'video.file' => 'Le fichier vidéo doit être valide.',
            'video.mimes' => 'La vidéo doit être au format MP4 ou MOV.',
            'video.max' => 'La vidéo ne peut pas dépasser 15 Mo.',
            'video_url.url' => 'Le lien vidéo doit être une URL valide.',
            'video_url.max' => 'Le lien vidéo est trop long.',
        ];
    }
}
