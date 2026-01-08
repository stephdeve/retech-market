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
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Smartphones', 'slug' => 'smartphones'],
            ['name' => 'Tablets', 'slug' => 'tablets'],
            ['name' => 'Accessoires', 'slug' => 'accessoires'],
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'Audio', 'slug' => 'audio'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
