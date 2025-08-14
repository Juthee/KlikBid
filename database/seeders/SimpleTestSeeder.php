<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class SimpleTestSeeder extends Seeder
{
    public function run(): void
    {
        // First, let's just add the level and icon to existing categories
        $updates = [
            ['name' => 'Land & Properties', 'icon' => '🏠', 'level' => 0],
            ['name' => 'Vehicles', 'icon' => '🚗', 'level' => 0],
            ['name' => 'Electronics', 'icon' => '📱', 'level' => 0],
            ['name' => 'Luxury Items', 'icon' => '💎', 'level' => 0],
        ];

        foreach ($updates as $update) {
            $category = Category::where('name', $update['name'])->first();
            if ($category) {
                $category->update([
                    'level' => $update['level'],
                    'icon' => $update['icon'],
                    'description' => 'Updated category'
                ]);
                $this->command->info("✅ Updated: " . $update['name']);
            }
        }

        // Add one test subcategory
        $electronicsCategory = Category::where('name', 'Electronics')->first();
        if ($electronicsCategory) {
            $mobilePhones = Category::where('name', 'Mobile Phones')->first();
            if (!$mobilePhones) {
                Category::create([
                    'name' => 'Mobile Phones',
                    'slug' => 'mobile-phones',
                    'parent_id' => $electronicsCategory->id,
                    'level' => 1,
                    'icon' => '📱',
                    'description' => 'Smartphones and mobile devices',
                    'is_active' => true,
                    'sort_order' => 1
                ]);
                $this->command->info("✅ Created subcategory: Mobile Phones");
            }
        }

        $this->command->info("🎉 Simple test seeder completed!");
    }
}
