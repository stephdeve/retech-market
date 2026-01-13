<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Smartphones', 'slug' => 'smartphones'],
            ['name' => 'Ordinateurs Portables', 'slug' => 'ordinateurs-portables'],
            ['name' => 'Tablettes', 'slug' => 'tablettes'],
            ['name' => 'Montres Connectées', 'slug' => 'montres-connectees'],
            ['name' => 'Écouteurs & Audio', 'slug' => 'ecouteurs-audio'],
            ['name' => 'Accessoires', 'slug' => 'accessoires'],
            ['name' => 'Consoles de Jeux', 'slug' => 'consoles-jeux'],
            ['name' => 'PC de Bureau', 'slug' => 'pc-bureau'],
            ['name' => 'Caméras & Photos', 'slug' => 'cameras-photos'],
            ['name' => 'Drones', 'slug' => 'drones'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
