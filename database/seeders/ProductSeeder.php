<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur principal pour les tests (s'il n'existe pas déjà)
        $mainUser = \App\Models\User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Utilisateur Test',
                'password' => bcrypt('password'), // Mot de passe: password
            ]
        );

        // Lui ajouter 5 produits
        \App\Models\Product::factory(5)->create([
            'user_id' => $mainUser->id,
        ]);

        // Créer 10 autres utilisateurs avec des produits aléatoires
        \App\Models\User::factory(10)->create()->each(function ($user) {
            \App\Models\Product::factory(rand(2, 5))->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
