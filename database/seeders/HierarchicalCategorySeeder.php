<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class HierarchicalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        Category::truncate();

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

        $sortOrder = 1;

        foreach ($categories as $categoryName => $categoryData) {
            // Create main category
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

            // Create subcategories
            $subSortOrder = 1;
            foreach ($categoryData['subcategories'] as $subName => $subIcon) {
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
            }
        }

        $this->command->info('✅ Created ' . Category::where('level', 0)->count() . ' main categories');
        $this->command->info('✅ Created ' . Category::where('level', 1)->count() . ' subcategories');
    }
}
