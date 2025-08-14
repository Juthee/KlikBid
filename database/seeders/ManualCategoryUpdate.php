<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManualCategoryUpdate extends Seeder
{
    public function run(): void
    {
        // Update existing categories with icons and level using raw SQL
        $updates = [
            ['name' => 'Land & Properties', 'icon' => '🏠', 'level' => 0, 'description' => 'Land plots and properties'],
            ['name' => 'Vehicles', 'icon' => '🚗', 'level' => 0, 'description' => 'Cars, motorcycles and vehicles'],
            ['name' => 'Electronics', 'icon' => '📱', 'level' => 0, 'description' => 'Mobile phones and electronics'],
            ['name' => 'Luxury Items', 'icon' => '💎', 'level' => 0, 'description' => 'Luxury and premium items'],
            ['name' => 'Machinery', 'icon' => '⚙️', 'level' => 0, 'description' => 'Industrial equipment'],
            ['name' => 'Antiques', 'icon' => '🏺', 'level' => 0, 'description' => 'Antique and collectible items'],
        ];

        foreach ($updates as $update) {
            $affected = DB::table('categories')
                ->where('name', $update['name'])
                ->update([
                    'level' => $update['level'],
                    'icon' => $update['icon'],
                    'description' => $update['description']
                ]);

            if ($affected > 0) {
                $this->command->info("✅ Updated: " . $update['name']);
            } else {
                $this->command->info("❌ Not found: " . $update['name']);
            }
        }

        // Add some subcategories using raw SQL
        $subcategories = [
            ['name' => 'Mobile Phones', 'parent_name' => 'Electronics', 'icon' => '📱'],
            ['name' => 'Laptop & PCs', 'parent_name' => 'Electronics', 'icon' => '💻'],
            ['name' => 'Cars', 'parent_name' => 'Vehicles', 'icon' => '🚗'],
            ['name' => 'Motorcycles', 'parent_name' => 'Vehicles', 'icon' => '🏍️'],
        ];

        foreach ($subcategories as $sub) {
            // Get parent category ID
            $parent = DB::table('categories')->where('name', $sub['parent_name'])->first();

            if ($parent) {
                // Check if subcategory already exists
                $exists = DB::table('categories')
                    ->where('name', $sub['name'])
                    ->where('parent_id', $parent->id)
                    ->exists();

                if (!$exists) {
                    DB::table('categories')->insert([
                        'name' => $sub['name'],
                        'slug' => strtolower(str_replace([' ', '&'], ['-', 'and'], $sub['name'])),
                        'parent_id' => $parent->id,
                        'level' => 1,
                        'icon' => $sub['icon'],
                        'description' => null,
                        'is_active' => true,
                        'sort_order' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $this->command->info("✅ Created subcategory: " . $sub['name']);
                } else {
                    $this->command->info("ℹ️  Already exists: " . $sub['name']);
                }
            }
        }

        $mainCount = DB::table('categories')->where('level', 0)->count();
        $subCount = DB::table('categories')->where('level', 1)->count();

        $this->command->info("🎉 Result: {$mainCount} main categories, {$subCount} subcategories");
    }
}
