<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and users for foreign keys
        $propertiesCategory = \App\Models\Category::where('slug', 'properties')->first();
        $vehiclesCategory = \App\Models\Category::where('slug', 'vehicles')->first();
        $electronicsCategory = \App\Models\Category::where('slug', 'electronics')->first();
        $luxuryCategory = \App\Models\Category::where('slug', 'luxury-lifestyle')->first();
        $landCategory = \App\Models\Category::where('slug', 'land')->first();

        $users = \App\Models\User::all();

        $auctions = [
            [
                'title' => 'Modern 3BR House in Colombo 7',
                'description' => 'Beautiful modern house with 3 bedrooms, 2 bathrooms, fully furnished kitchen, parking space, and garden. Located in prestigious Colombo 7 area with easy access to schools and shopping centers.',
                'category_id' => $propertiesCategory->id,
                'base_price' => 250000000, // Rs 2.5M in cents
                'reserve_price' => 280000000,
                'deposit_amount' => 500000, // Rs 5,000
                'address_line' => 'Reid Avenue, Colombo 7',
                'district' => 'Colombo',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subDays(2),
                'end_at' => now()->addDays(3),
            ],
            [
                'title' => 'Toyota Prius 2020 Hybrid',
                'description' => 'Excellent condition Toyota Prius 2020 model. Only 25,000km driven. Full service history, accident-free, dual airbags, GPS navigation, reverse camera. Perfect for Colombo traffic.',
                'category_id' => $vehiclesCategory->id,
                'base_price' => 400000000, // Rs 4M in cents
                'reserve_price' => 420000000,
                'deposit_amount' => 500000, // Rs 5,000
                'address_line' => 'Nugegoda',
                'district' => 'Colombo',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subDays(1),
                'end_at' => now()->addHours(18),
            ],
            [
                'title' => 'iPhone 14 Pro Max 256GB - Like New',
                'description' => 'iPhone 14 Pro Max 256GB in Space Black. Purchased 6 months ago, barely used. Includes original box, charger, and screen protector already applied. Battery health 100%.',
                'category_id' => $electronicsCategory->id,
                'base_price' => 35000000, // Rs 350,000 in cents
                'reserve_price' => 38000000,
                'deposit_amount' => 100000, // Rs 1,000
                'address_line' => 'Kandy City Center',
                'district' => 'Kandy',
                'province' => 'Central',
                'status' => 'active',
                'start_at' => now()->subHours(12),
                'end_at' => now()->addHours(6),
            ],
            [
                'title' => '22K Gold Necklace Set - Traditional Design',
                'description' => 'Authentic 22K gold necklace set with traditional Sri Lankan design. Weight: 25 grams. Includes matching earrings. Perfect for weddings and special occasions. Certificate included.',
                'category_id' => $luxuryCategory->id,
                'base_price' => 40000000, // Rs 400,000 in cents
                'reserve_price' => 42500000,
                'deposit_amount' => 100000, // Rs 1,000
                'address_line' => 'Pettah, Colombo 11',
                'district' => 'Colombo',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subDays(1),
                'end_at' => now()->addDays(2),
            ],
            [
                'title' => 'BMW X3 2019 - Premium SUV',
                'description' => 'Luxury BMW X3 2019 model in pearl white. 45,000km, full BMW service history, leather seats, panoramic sunroof, all-wheel drive. Single owner, garage kept.',
                'category_id' => $vehiclesCategory->id,
                'base_price' => 850000000, // Rs 8.5M in cents
                'reserve_price' => 900000000,
                'deposit_amount' => 500000, // Rs 5,000
                'address_line' => 'Galle Road, Mount Lavinia',
                'district' => 'Colombo',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subDays(3),
                'end_at' => now()->addDays(4),
            ],
            [
                'title' => 'Commercial Land - Gampaha Main Road',
                'description' => 'Prime commercial land on Gampaha main road. 20 perches, clear title, road frontage 60 feet. Perfect for showroom, restaurant, or commercial building. High traffic area.',
                'category_id' => $landCategory->id,
                'base_price' => 180000000, // Rs 1.8M in cents
                'reserve_price' => 200000000,
                'deposit_amount' => 500000, // Rs 5,000
                'address_line' => 'Main Street, Gampaha',
                'district' => 'Gampaha',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subDays(1),
                'end_at' => now()->addDays(5),
            ],
            [
                'title' => 'MacBook Pro M2 14-inch - Barely Used',
                'description' => 'MacBook Pro 14-inch with M2 chip, 16GB RAM, 512GB SSD. Space Gray color. Purchased 3 months ago for work project that got cancelled. Original box and warranty.',
                'category_id' => $electronicsCategory->id,
                'base_price' => 45000000, // Rs 450,000 in cents
                'reserve_price' => 48000000,
                'deposit_amount' => 100000, // Rs 1,000
                'address_line' => 'Wellawatta',
                'district' => 'Colombo',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subHours(6),
                'end_at' => now()->addDays(1),
            ],
            [
                'title' => 'Luxury Apartment - Colombo 3',
                'description' => '2BR luxury apartment on 15th floor with sea view. Swimming pool, gym, 24/7 security, covered parking. Fully furnished with imported furniture and appliances.',
                'category_id' => $propertiesCategory->id,
                'base_price' => 120000000, // Rs 1.2M in cents
                'reserve_price' => 135000000,
                'deposit_amount' => 500000, // Rs 5,000
                'address_line' => 'Marine Drive, Colombo 3',
                'district' => 'Colombo',
                'province' => 'Western',
                'status' => 'active',
                'start_at' => now()->subDays(2),
                'end_at' => now()->addDays(6),
            ]
        ];

        foreach ($auctions as $auctionData) {
            \App\Models\Auction::create(array_merge($auctionData, [
                'user_id' => $users->random()->id,
            ]));
        }
    }
}
