<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main categories
        $categories = [
            [
                'name' => 'Land',
                'slug' => 'land',
                'subcategories' => [
                    'Agricultural Land',
                    'Residential Land',
                    'Commercial Plots'
                ]
            ],
            [
                'name' => 'Properties',
                'slug' => 'properties',
                'subcategories' => [
                    'Houses',
                    'Apartments',
                    'Commercial Buildings',
                    'Warehouse'
                ]
            ],
            [
                'name' => 'Vehicles',
                'slug' => 'vehicles',
                'subcategories' => [
                    'Cars',
                    'Motorcycles',
                    'Trucks & Vans',
                    'Buses',
                    'Tractors & Heavy Machinery'
                ]
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'subcategories' => [
                    'Mobile Phones',
                    'Laptop & PCs',
                    'TVs & Home Appliance',
                    'Cameras & Drones'
                ]
            ],
            [
                'name' => 'Furniture & Home Decor',
                'slug' => 'furniture-home-decor',
                'subcategories' => [
                    'Sofas, Beds & Tables',
                    'Antiques & Collectibles',
                    'Office Furniture'
                ]
            ],
            [
                'name' => 'Machinery & Tools',
                'slug' => 'machinery-tools',
                'subcategories' => [
                    'Industrial Equipment',
                    'Construction Tools',
                    'Farming Equipment'
                ]
            ],
            [
                'name' => 'Luxury & Lifestyle',
                'slug' => 'luxury-lifestyle',
                'subcategories' => [
                    'Watches',
                    'Jewelry',
                    'Gems',
                    'Designer Items'
                ]
            ],
            [
                'name' => 'Business Assets',
                'slug' => 'business-assets',
                'subcategories' => [
                    'Office Equipment',
                    'Shops & Franchises',
                    'Licenses & Permits'
                ]
            ],
            [
                'name' => 'Miscellaneous',
                'slug' => 'miscellaneous',
                'subcategories' => [
                    'Pets & Animals',
                    'Rare Collectibles',
                    'Art & Memorabilia'
                ]
            ]
        ];

        foreach ($categories as $index => $categoryData) {
            // Create main category
            $category = \App\Models\Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => $index + 1
            ]);

            // Create subcategories
            foreach ($categoryData['subcategories'] as $subIndex => $subName) {
                \App\Models\Category::create([
                    'name' => $subName,
                    'slug' => \Illuminate\Support\Str::slug($subName),
                    'parent_id' => $category->id,
                    'is_active' => true,
                    'sort_order' => $subIndex + 1
                ]);
            }
        }
    }
}
