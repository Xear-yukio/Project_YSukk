<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Phones', 'slug' => 'phones', 'icon' => 'fas fa-mobile-alt'],
            ['name' => 'Computers', 'slug' => 'computers', 'icon' => 'fas fa-laptop'],
            ['name' => 'Smartwatch', 'slug' => 'smartwatch', 'icon' => 'fas fa-clock'],
            ['name' => 'Camera', 'slug' => 'camera', 'icon' => 'fas fa-camera'],
            ['name' => 'Headphone', 'slug' => 'headphone', 'icon' => 'fas fa-headphones'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'icon' => 'fas fa-gamepad'],
            ['name' => 'Toys', 'slug' => 'toys', 'icon' => 'fas fa-puzzle-piece'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'icon' => 'fas fa-tshirt'],
            ['name' => 'Sports', 'slug' => 'sports', 'icon' => 'fas fa-running'],
            ['name' => 'Beauty', 'slug' => 'beauty', 'icon' => 'fas fa-magic'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
