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
        Schema::table('products', function (Blueprint $table) {
            // Type de transaction: sale | trade | both
            $table->string('transaction_type', 10)->default('sale');
            // Liste de souhaits pour le troc (ex: "Contre quoi voulez-vous échanger ?")
            $table->string('trade_wishlist')->nullable();
            // Localisation de l'annonce
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['transaction_type', 'trade_wishlist', 'city']);
        });
    }
};
