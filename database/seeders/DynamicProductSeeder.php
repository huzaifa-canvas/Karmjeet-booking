<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DynamicProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the folder where images will be placed.
        // Change this path to the actual folder on the server if needed.
        $imageDirectory = public_path('images/products');

        if (!File::exists($imageDirectory)) {
            $this->command->error("Directory not found at: {$imageDirectory}");
            $this->command->info("Please place your images there and run the seeder again.");
            return;
        }

        $files = File::files($imageDirectory);

        if (empty($files)) {
            $this->command->warn("No images found in {$imageDirectory}");
            return;
        }

        $count = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $name = trim($name);
            $name = ucwords($name);

            // Determine category based on the file name
            $category = $this->getCategory($name);
            
            // Create category attribute if it doesn't exist
            if ($category !== 'Uncategorized') {
                ProductAttribute::firstOrCreate(['type' => 'category', 'name' => $category]);
            }

            // Generate unique slug
            $slug = Str::slug($name);
            $originalSlug = $slug;
            $slugSuffix = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$slugSuffix}";
                $slugSuffix++;
            }

            // Create the product
            Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => null,
                'price' => 147.00,
                'image' => 'images/products/' . $filename,
                'category' => $category,
                'brand' => null,
                'stock' => 10,
                'status' => 'active',
                'featured' => 0,
            ]);

            $count++;
            $this->command->info("Created product: {$name} (Category: {$category})");
        }

        $this->command->info("Successfully seeded {$count} products from images!");
    }

    /**
     * Scan the name to determine a category.
     *
     * @param string $name
     * @return string
     */
    private function getCategory($name)
    {
        $name = strtolower($name);
        
        if (str_contains($name, 'hoody') || str_contains($name, 'hoodie')) return 'Hoodies';
        if (str_contains($name, 'glove') || str_contains($name, 'leather upgrade')) return 'Gloves';
        if (str_contains($name, 'spat')) return 'Spats';
        if (str_contains($name, 'track suit') || str_contains($name, 'tracksuit')) return 'Track Suits';
        if (str_contains($name, 'shin') || str_contains($name, 'instep')) return 'Shin Guards';
        if (str_contains($name, 'mouthguard')) return 'Mouthguards';
        if (str_contains($name, 'head gear') || str_contains($name, 'headgear')) return 'Head Gear';
        if (str_contains($name, 'kimono') || str_contains($name, 'belt')) return 'BJJ Gear';
        if (str_contains($name, 'short')) return 'Shorts';
        if (str_contains($name, 'tank')) return 'Tank Tops';
        if (str_contains($name, 'shirt') || str_contains($name, 'tshirt') || str_contains($name, 't-shirt')) return 'Shirts';
        if (str_contains($name, 'wrap')) return 'Hand Wraps';
        if (str_contains($name, 'mp4') || str_contains($name, 'remix') || str_contains($name, 'ricky')) return 'Videos';
        
        return 'Uncategorized';
    }
}
