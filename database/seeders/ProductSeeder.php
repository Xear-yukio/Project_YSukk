<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all()->pluck('id', 'slug')->toArray();

        $products = [
            [
                'name' => 'Havic HV G-92 Stik',
                'price' => 120000,
                'old_price' => 150000,
                'discount' => '40%',
                'rating' => 5,
                'reviews' => 88,
                'description' => 'Berkualitas tinggi dengan perekat/ber-saluran udara untuk pemasangan bebas gelembung & pelepasan yang mudah tanpa meninggalkan bekas.',
                'colors' => ['#db4444', '#000'],
                'main_image' => 'https://images.unsplash.com/photo-1592840331052-16e15c2c6f95?w=600&h=600&fit=crop',
                'gallery' => [
                    'https://images.unsplash.com/photo-1592840331052-16e15c2c6f95?w=120&h=120&fit=crop',
                    'https://images.unsplash.com/photo-1621259182978-f09e5e2ca8d6?w=120&h=120&fit=crop'
                ],
                'breadcrumbs' => ['Home', 'Gaming', 'Controller'],
                'category_slug' => 'gaming'
            ],
            [
                'name' => 'Drone Explorer 4K',
                'price' => 1250000,
                'rating' => 5,
                'reviews' => 125,
                'description' => 'Drone quadcopter dengan kamera 4K UHD dan fitur auto-stabilization.',
                'colors' => ['#ffffff', '#000000'],
                'main_image' => 'https://images.unsplash.com/photo-1507582020474-9a35b7d455d9?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1507582020474-9a35b7d455d9?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Electronics', 'Drone'],
                'badge' => 'NEW',
                'category_slug' => 'camera'
            ],
            [
                'name' => 'Sepatu Sepak Bola Jr. Zoom',
                'price' => 876000,
                'rating' => 5,
                'reviews' => 35,
                'description' => 'Sepatu sepak bola berkualitas tinggi untuk performa maksimal di lapangan.',
                'colors' => ['#ffff00', '#000'],
                'main_image' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1511886929837-354d827aae26?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Sports', 'Shoes'],
                'category_slug' => 'sports'
            ],
            [
                'name' => 'Premium Headphone Wireless Bass',
                'price' => 299000,
                'old_price' => 499000,
                'discount' => '40%',
                'rating' => 5,
                'reviews' => 256,
                'description' => 'Headphone nirkabel dengan bass yang kuat dan audio jernih.',
                'colors' => ['#000', '#fff'],
                'main_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Audio', 'Headphone'],
                'badge' => '-40%',
                'category_slug' => 'headphone'
            ],
            [
                'name' => 'DSLR Camera Pro D850',
                'price' => 12499000,
                'old_price' => 13999000,
                'discount' => '10%',
                'rating' => 5,
                'reviews' => 42,
                'description' => 'Kamera DSLR profesional dengan sensor full-frame 45.7 MP.',
                'colors' => ['#000'],
                'main_image' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Electronics', 'Camera'],
                'badge' => '-10%',
                'category_slug' => 'camera'
            ],
            [
                'name' => 'Jaket Bomber Premium Unisex',
                'price' => 459000,
                'rating' => 4,
                'reviews' => 189,
                'description' => 'Jaket bomber dengan bahan premium, nyaman dipakai.',
                'colors' => ['#000', '#2f4f4f'],
                'main_image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Fashion', 'Jacket'],
                'category_slug' => 'fashion'
            ],
            [
                'name' => 'Smartphone Pro Max 256GB',
                'price' => 18499000,
                'old_price' => 19999000,
                'discount' => '7%',
                'rating' => 5,
                'reviews' => 512,
                'description' => 'Smartphone flagship terbaru dengan performa luar biasa.',
                'colors' => ['#000', '#fff', '#ffd700'],
                'main_image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Phones', 'Smartphone'],
                'category_slug' => 'phones'
            ],
            [
                'name' => 'Gaming Laptop Ultra G15',
                'price' => 22999000,
                'rating' => 4.5,
                'reviews' => 45,
                'description' => 'Laptop gaming bertenaga dengan RTX series.',
                'colors' => ['#000'],
                'main_image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Computers', 'Laptop'],
                'category_slug' => 'computers'
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'price' => 850000,
                'rating' => 5,
                'reviews' => 120,
                'description' => 'Keyboard mekanik dengan lampu RGB yang dapat disesuaikan.',
                'colors' => ['#000', '#fff'],
                'main_image' => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Gaming', 'Keyboard'],
                'category_slug' => 'gaming'
            ],
            [
                'name' => 'Ergonomic Gaming Mouse',
                'price' => 450000,
                'old_price' => 600000,
                'discount' => '25%',
                'rating' => 4.5,
                'reviews' => 230,
                'description' => 'Mouse gaming ergonomis untuk kenyamanan maksimal saat bermain.',
                'colors' => ['#000'],
                'main_image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Gaming', 'Mouse'],
                'badge' => '-25%',
                'category_slug' => 'gaming'
            ],
            [
                'name' => 'Smartwatch Series 7',
                'price' => 3500000,
                'rating' => 5,
                'reviews' => 450,
                'description' => 'Smartwatch canggih with various health features.',
                'colors' => ['#000', '#444'],
                'main_image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Electronics', 'Smartwatch'],
                'badge' => 'NEW',
                'category_slug' => 'smartwatch'
            ],
            [
                'name' => 'Modern Coffee Maker',
                'price' => 1250000,
                'rating' => 4.8,
                'reviews' => 75,
                'description' => 'Automatic coffee maker for delicious results.',
                'colors' => ['#silver', '#black'],
                'main_image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Appliances', 'Kitchen'],
                'category_slug' => 'home-decor'
            ],
            [
                'name' => 'Portable Bluetooth Speaker',
                'price' => 450000,
                'rating' => 4.2,
                'reviews' => 150,
                'description' => 'Portable bluetooth speaker with clear sound and strong bass.',
                'colors' => ['#000', '#blue'],
                'main_image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Audio', 'Speaker'],
                'category_slug' => 'headphone'
            ],
            [
                'name' => 'Casual Canvas Backpack',
                'price' => 350000,
                'old_price' => 450000,
                'discount' => '22%',
                'rating' => 4.5,
                'reviews' => 85,
                'description' => 'Durable casual backpack for daily activities.',
                'colors' => ['#grey', '#black'],
                'main_image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Fashion', 'Bag'],
                'badge' => '-22%',
                'category_slug' => 'fashion'
            ],
            [
                'name' => 'Minimalist LED Desk Lamp',
                'price' => 250000,
                'rating' => 4.7,
                'reviews' => 40,
                'description' => 'Minimalist LED desk lamp with brightness settings.',
                'colors' => ['#black', '#white'],
                'main_image' => 'https://images.unsplash.com/photo-1534073828943-f801091bb18c?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1534073828943-f801091bb18c?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Home Decor', 'Lighting'],
                'category_slug' => 'home-decor'
            ],
            [
                'name' => 'Compact Power Bank 10000mAh',
                'price' => 199000,
                'rating' => 4.6,
                'reviews' => 310,
                'description' => 'High capacity power bank with compact design.',
                'colors' => ['#black', '#silver'],
                'main_image' => 'https://images.unsplash.com/photo-1609592424089-98904df1665a?w=600&h=600&fit=crop',
                'gallery' => ['https://images.unsplash.com/photo-1609592424089-98904df1665a?w=300&h=300&fit=crop'],
                'breadcrumbs' => ['Home', 'Phones', 'Accessories'],
                'category_slug' => 'phones'
            ]
        ];

        Product::query()->delete();

        foreach ($products as $p) {
            $cat_slug = $p['category_slug'];
            unset($p['category_slug']);
            $p['category_id'] = $categories[$cat_slug] ?? null;
            Product::create($p);
        }
    }
}

