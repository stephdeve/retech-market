<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Vendeur
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Catégorie
            $table->string('name'); // Nom du produit
            $table->text('description'); // Description détaillée
            $table->decimal('price', 10, 2); // Prix (max 99,999,999.99)
            $table->string('image_path')->nullable(); // Chemin de l'image (optionnel)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
