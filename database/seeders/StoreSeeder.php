<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = collect([
            [
                'name' => 'Audio',
                'description' => 'Wireless audio gear designed for focused listening and all-day comfort.',
                'image_url' => $this->photo('photo-1516280440614-37939bbacd81'),
            ],
            [
                'name' => 'Workspace',
                'description' => 'Desk-friendly accessories that keep creative setups fast and uncluttered.',
                'image_url' => $this->photo('photo-1497366754035-f200968a6e72'),
            ],
            [
                'name' => 'Wearables',
                'description' => 'Smart essentials that blend performance tracking with everyday style.',
                'image_url' => $this->photo('photo-1523275335684-37898b6baf30'),
            ],
            [
                'name' => 'Travel',
                'description' => 'Compact devices and accessories for life on the move.',
                'image_url' => $this->photo('photo-1436491865332-7a61a109cc05'),
            ],
        ])->mapWithKeys(function (array $category): array {
            return [
                $category['name'] => Category::query()->updateOrCreate(
                    ['slug' => Str::slug($category['name'])],
                    [
                        'name' => $category['name'],
                        'description' => $category['description'],
                        'image_url' => $category['image_url'],
                    ],
                ),
            ];
        });

        $products = [
            [
                'category' => 'Audio',
                'name' => 'Auraloop Pro Headphones',
                'description' => 'Adaptive noise control, soft memory foam cups, and a balanced studio-inspired sound profile.',
                'price' => 189.00,
                'compare_price' => 229.00,
                'sku' => 'AUD-1001',
                'inventory' => 28,
                'is_featured' => true,
                'attributes' => ['Battery' => '38 hours', 'Connection' => 'Bluetooth 5.4', 'Weight' => '245 g'],
                'image_url' => $this->photo('photo-1505740420928-5e560c06d30e'),
            ],
            [
                'category' => 'Audio',
                'name' => 'Pulse Mini Speaker',
                'description' => 'Portable stereo speaker with IPX7 water resistance and punchy bass for small rooms or trips.',
                'price' => 79.00,
                'compare_price' => 99.00,
                'sku' => 'AUD-1002',
                'inventory' => 64,
                'is_featured' => true,
                'attributes' => ['Battery' => '18 hours', 'Charging' => 'USB-C', 'Finish' => 'Matte shell'],
                'image_url' => $this->photo('photo-1608043152269-423dbba4e7e1'),
            ],
            [
                'category' => 'Workspace',
                'name' => 'Lift Mechanical Keyboard',
                'description' => 'Low-profile tactile switches, warm backlight, and a compact aluminum body for hybrid work.',
                'price' => 139.00,
                'compare_price' => 169.00,
                'sku' => 'WRK-2001',
                'inventory' => 35,
                'is_featured' => true,
                'attributes' => ['Switches' => 'Tactile', 'Layout' => '75%', 'Connection' => 'Tri-mode'],
                'image_url' => $this->photo('photo-1587829741301-dc798b83add3'),
            ],
            [
                'category' => 'Workspace',
                'name' => 'Canvas 4K Monitor Light',
                'description' => 'A monitor-mounted light bar that reduces glare and keeps your desk calm during long sessions.',
                'price' => 109.00,
                'compare_price' => null,
                'sku' => 'WRK-2002',
                'inventory' => 19,
                'is_featured' => false,
                'attributes' => ['Brightness' => 'Stepless', 'Color temp' => '2700K-6500K', 'Control' => 'Wireless dial'],
                'image_url' => '/images/4k.png',
            ],
            [
                'category' => 'Wearables',
                'name' => 'Orbit Fitness Watch',
                'description' => 'A slim health companion with sleep scoring, route tracking, and a five-day battery.',
                'price' => 159.00,
                'compare_price' => 199.00,
                'sku' => 'WAR-3001',
                'inventory' => 41,
                'is_featured' => true,
                'attributes' => ['Sensors' => 'Heart rate, SpO2', 'Battery' => '5 days', 'Water resistance' => '5 ATM'],
                'image_url' => $this->photo('photo-1557438159-51eec7a6c9e8'),
            ],
            [
                'category' => 'Wearables',
                'name' => 'Flow Smart Ring',
                'description' => 'Ultra-light titanium smart ring built for recovery insights, movement tracking, and notifications.',
                'price' => 129.00,
                'compare_price' => null,
                'sku' => 'WAR-3002',
                'inventory' => 52,
                'is_featured' => false,
                'attributes' => ['Material' => 'Titanium', 'Battery' => '6 days', 'Sizes' => '7-12'],
                'image_url' => $this->photo('photo-1605100804763-247f67b3557e'),
            ],
            [
                'category' => 'Travel',
                'name' => 'Glide 65W Travel Charger',
                'description' => 'Compact GaN charger with dual USB-C ports and one USB-A output for phone-to-laptop setups.',
                'price' => 49.00,
                'compare_price' => 59.00,
                'sku' => 'TRV-4001',
                'inventory' => 88,
                'is_featured' => true,
                'attributes' => ['Power' => '65W', 'Ports' => '2x USB-C, 1x USB-A', 'Weight' => '126 g'],
                'image_url' => $this->photo('photo-1609091839311-d5365f9ff1c5'),
            ],
            [
                'category' => 'Travel',
                'name' => 'Wayfinder Tech Pouch',
                'description' => 'Expandable organizer with cable loops, SD card sleeves, and weather-ready fabric.',
                'price' => 39.00,
                'compare_price' => null,
                'sku' => 'TRV-4002',
                'inventory' => 73,
                'is_featured' => false,
                'attributes' => ['Material' => 'Recycled nylon', 'Compartments' => '9', 'Closure' => 'Waterproof zip'],
                'image_url' => $this->photo('photo-1491637639811-60e2756cc1c7'),
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'category_id' => $categories[$product['category']]->id,
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'compare_price' => $product['compare_price'],
                    'image_url' => $product['image_url'],
                    'inventory' => $product['inventory'],
                    'is_featured' => $product['is_featured'],
                    'is_active' => true,
                    'attributes' => $product['attributes'],
                ],
            );
        }
    }

    private function photo(string $photoId): string
    {
        return "https://images.unsplash.com/{$photoId}?auto=format&fit=crop&w=1000&q=82";
    }
}
