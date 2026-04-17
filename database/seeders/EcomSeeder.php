<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductAttribute;

class EcomSeeder extends Seeder
{
    public function run()
    {
        // 1. Create product attributes (categories and brands)
        $categories = ['Fitness Equipment', 'Apparel', 'Supplements', 'Accessories'];
        $brands = ['Nike', 'Adidas', 'Under Armour', 'Optimum Nutrition', 'GymShark'];

        foreach ($categories as $cat) {
            ProductAttribute::firstOrCreate(['type' => 'category', 'name' => $cat]);
        }

        foreach ($brands as $brand) {
            ProductAttribute::firstOrCreate(['type' => 'brand', 'name' => $brand]);
        }

        // 2. Create products
        $products = [
            [
                'name' => 'Premium Workout Mat',
                'description' => 'High-density, anti-tear exercise yoga mat with carrying strap.',
                'price' => 29.99,
                'stock' => 50,
                'category' => 'Fitness Equipment',
                'brand' => 'Nike',
                'status' => 'active',
                'featured' => 1,
            ],
            [
                'name' => 'Men\'s Compression T-Shirt',
                'description' => 'Breathable compression athletic shirt for intense workouts.',
                'price' => 24.50,
                'sale_price' => 19.99,
                'stock' => 100,
                'category' => 'Apparel',
                'brand' => 'Under Armour',
                'status' => 'active',
                'featured' => 0,
            ],
            [
                'name' => 'Whey Protein Powder 5lbs',
                'description' => '100% Whey Protein Isolate in Double Rich Chocolate flavor.',
                'price' => 64.99,
                'stock' => 30,
                'category' => 'Supplements',
                'brand' => 'Optimum Nutrition',
                'status' => 'active',
                'featured' => 1,
            ],
            [
                'name' => 'Adjustable Dumbbell Set',
                'description' => 'Pair of adjustable dumbbells up to 52.5 lbs each.',
                'price' => 349.00,
                'sale_price' => 299.99,
                'stock' => 15,
                'category' => 'Fitness Equipment',
                'brand' => 'Adidas',
                'status' => 'active',
                'featured' => 1,
            ],
            [
                'name' => 'Women\'s High-Waist Leggings',
                'description' => 'Squat-proof seamless high-waist workout leggings.',
                'price' => 45.00,
                'stock' => 200,
                'category' => 'Apparel',
                'brand' => 'GymShark',
                'status' => 'active',
                'featured' => 0,
            ],
            [
                'name' => 'Pre-Workout Energy Powder',
                'description' => 'Explosive energy and pump pre-workout supplement.',
                'price' => 35.00,
                'stock' => 60,
                'category' => 'Supplements',
                'brand' => 'Optimum Nutrition',
                'status' => 'active',
                'featured' => 0,
            ],
            [
                'name' => 'Weightlifting Belt',
                'description' => 'Leather weightlifting belt for maximum back support.',
                'price' => 45.99,
                'stock' => 45,
                'category' => 'Accessories',
                'brand' => 'Nike',
                'status' => 'active',
                'featured' => 0,
            ],
            [
                'name' => 'Gym Shaker Bottle',
                'description' => '24oz shaker bottle with wire whisk ball.',
                'price' => 9.99,
                'stock' => 150,
                'category' => 'Accessories',
                'brand' => 'Under Armour',
                'status' => 'active',
                'featured' => 0,
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
