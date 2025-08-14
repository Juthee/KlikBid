<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's add the missing columns to existing categories
        $existingCategories = Category::all();

        foreach ($existingCategories as $category) {
            $category->update([
                'level' => 0, // Make existing categories main categories
                'icon' => $this->getIconForCategory($category->name),
                'description' => $this->getDescriptionForCategory($category->name)
            ]);
        }

        // Now add new hierarchical categories
        $categories = [
            'Land' => [
                'icon' => '🏞️',
                'description' => 'Agricultural, residential and commercial land plots',
                'subcategories' => [
                    'Agricultural Land' => '🌾',
                    'Residential Land' => '🏡',
                    'Commercial Plots' => '🏢'
                ]
            ],
            'Properties' => [
                'icon' => '🏠',
                'description' => 'Houses, apartments and commercial buildings',
                'subcategories' => [
                    'Houses' => '🏠',
                    'Apartments' => '🏘️',
                    'Commercial Buildings' => '🏢',
                    'Warehouse' => '🏭'
                ]
            ],
            'Vehicles' => [
                'icon' => '🚗',
                'description' => 'Cars, motorcycles and all types of vehicles',
                'subcategories' => [
                    'Cars' => '🚗',
                    'Motorcycles' => '🏍️',
                    'Trucks & Vans' => '🚛',
                    'Buses' => '🚌',
                    'Tractors & Heavy Machinery' => '🚜'
                ]
            ],
            'Electronics' => [
                'icon' => '📱',
                'description' => 'Mobile phones, laptops and electronic devices',
                'subcategories' => [
                    'Mobile Phones' => '📱',
                    'Laptop & PCs' => '💻',
                    'TVs & Home Appliance' => '📺',
                    'Cameras & Drones' => '📷'
                ]
            ],
            'Furniture & Home Decor' => [
                'icon' => '🪑',
                'description' => 'Furniture, antiques and home decoration items',
                'subcategories' => [
                    'Sofas, Beds & Tables' => '🛏️',
                    'Antiques & Collectibles' => '🏺',
                    'Office Furniture' => '🪑'
                ]
            ],
            'Machinery & Tools' => [
                'icon' => '⚙️',
                'description' => 'Industrial equipment and construction tools',
                'subcategories' => [
                    'Industrial Equipment' => '🏭',
                    'Construction Tools' => '🔨',
                    'Farming Equipment' => '🚜'
                ]
            ],
            'Luxury & Lifestyle' => [
                'icon' => '💎',
                'description' => 'Watches, jewelry, gems and designer items',
                'subcategories' => [
                    'Watches' => '⌚',
                    'Jewelry' => '💍',
                    'Gems' => '💎',
                    'Designer Items' => '👜'
                ]
            ],
            'Business Assets' => [
                'icon' => '💼',
                'description' => 'Office equipment, shops and business licenses',
                'subcategories' => [
                    'Office Equipment' => '🖥️',
                    'Shops & Franchises' => '🏪',
                    'Licenses & Permits' => '📄'
                ]
            ],
            'Miscellaneous' => [
                'icon' => '📦',
                'description' => 'Pets, collectibles and unique items',
                'subcategories' => [
                    'Pets & Animals' => '🐕',
                    'Rare Collectibles' => '🏆',
                    'Art & Memorabilia' => '🎨'
                ]
            ]
        ];

        $sortOrder = 100; // Start from 100 to avoid conflicts

        foreach ($categories as $categoryName => $categoryData) {
            // Check if main category already exists
            $mainCategory = Category::where('name', $categoryName)->first();

            if (!$mainCategory) {
                // Create new main category
                $mainCategory = Category::create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'parent_id' => null,
                    'level' => 0,
                    'icon' => $categoryData['icon'],
                    'description' => $categoryData['description'],
                    'is_active' => true,
                    'sort_order' => $sortOrder++
                ]);

                $this->command->info("✅ Created main category: {$categoryName}");
            } else {
                // Update existing category
                $mainCategory->update([
                    'icon' => $categoryData['icon'],
                    'description' => $categoryData['description'],
                    'level' => 0
                ]);

                $this->command->info("✅ Updated main category: {$categoryName}");
            }

            // Create subcategories
            $subSortOrder = 1;
            foreach ($categoryData['subcategories'] as $subName => $subIcon) {
                $subCategory = Category::where('name', $subName)->where('parent_id', $mainCategory->id)->first();

                if (!$subCategory) {
                    Category::create([
                        'name' => $subName,
                        'slug' => Str::slug($subName),
                        'parent_id' => $mainCategory->id,
                        'level' => 1,
                        'icon' => $subIcon,
                        'description' => null,
                        'is_active' => true,
                        'sort_order' => $subSortOrder++
                    ]);

                    $this->command->info("  ➕ Added subcategory: {$subName}");
                }
            }
        }

        $mainCount = Category::where('level', 0)->count();
        $subCount = Category::where('level', 1)->count();

        $this->command->info("🎉 Final result: {$mainCount} main categories, {$subCount} subcategories");
    }

    /**
     * Get appropriate icon for existing categories
     */
    private function getIconForCategory($name)
    {
        $icons = [
            'Land & Properties' => '🏠',
            'Vehicles' => '🚗',
            'Electronics' => '📱',
            'Luxury Items' => '💎',
            'Machinery' => '⚙️',
            'Antiques' => '🏺'
        ];

        return $icons[$name] ?? '📦';
    }

    /**
     * Get appropriate description for existing categories
     */
    private function getDescriptionForCategory($name)
    {
        $descriptions = [
            'Land & Properties' => 'Land plots, houses, and commercial properties',
            'Vehicles' => 'Cars, motorcycles, and all types of vehicles',
            'Electronics' => 'Mobile phones, computers, and electronic devices',
            'Luxury Items' => 'Watches, jewelry, and designer items',
            'Machinery' => 'Industrial equipment and tools',
            'Antiques' => 'Collectibles and antique items'
        ];

        return $descriptions[$name] ?? 'Various items in this category';
    }
}
