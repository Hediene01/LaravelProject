<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminUser = User::query()->where('email', 'admin@example.com')->first();
        $customerUser = User::query()->where('email', 'customer@example.com')->first();

        $brands = collect([
            [
                'name' => 'Astra Audio',
                'description' => 'Premium listening gear built for immersive sound and all-day comfort.',
                'logo_url' => $this->photo('photo-1546435770-a3e426bf472b'),
            ],
            [
                'name' => 'Northline Studio',
                'description' => 'Refined desk accessories and creative tools for modern workspaces.',
                'logo_url' => $this->photo('photo-1516321318423-f06f85e504b3'),
            ],
            [
                'name' => 'Orbit Active',
                'description' => 'Wearable technology focused on recovery, movement, and lightweight form.',
                'logo_url' => $this->photo('photo-1511499767150-a48a237f0083'),
            ],
            [
                'name' => 'Waypoint Supply',
                'description' => 'Travel accessories and compact chargers designed for moving fast.',
                'logo_url' => $this->photo('photo-1484704849700-f032a568e944'),
            ],
        ])->mapWithKeys(function (array $brand): array {
            return [
                $brand['name'] => Brand::query()->updateOrCreate(
                    ['slug' => Str::slug($brand['name'])],
                    $brand,
                ),
            ];
        });

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
                'brand' => 'Astra Audio',
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
                'brand' => 'Astra Audio',
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
                'brand' => 'Northline Studio',
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
                'brand' => 'Northline Studio',
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
                'brand' => 'Orbit Active',
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
                'brand' => 'Orbit Active',
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
                'brand' => 'Waypoint Supply',
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
                'brand' => 'Waypoint Supply',
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
                    'brand_id' => $brands[$product['brand']]->id,
                    'user_id' => $adminUser?->id,
                    'name' => $product['name'],
                    'keywords' => str_replace('-', ', ', Str::slug($product['name'])),
                    'slug' => Str::slug($product['name']),
                    'description' => $product['description'],
                    'detail' => $product['description'].' Designed as seeded demo content for the admin CRUD and storefront pages.',
                    'meta_title' => $product['name'].' | '.$product['brand'],
                    'meta_description' => 'Shop '.$product['name'].' from '.$product['brand'].' in the Luna Commerce demo catalog.',
                    'price' => $product['price'],
                    'compare_price' => $product['compare_price'],
                    'image_url' => $product['image_url'],
                    'gallery' => [
                        $product['image_url'],
                        $this->photo('photo-1519389950473-47ba0277781c'),
                    ],
                    'inventory' => $product['inventory'],
                    'min_stock' => 5,
                    'discount' => $product['compare_price'] ? (int) max(0, round((1 - ($product['price'] / $product['compare_price'])) * 100)) : 0,
                    'average_rating' => 0,
                    'reviews_count' => 0,
                    'is_featured' => $product['is_featured'],
                    'is_active' => true,
                    'attributes' => $product['attributes'],
                ],
            );
        }

        if ($customerUser !== null) {
            $reviewProduct = Product::query()->where('sku', 'AUD-1001')->first();
            $wishlistProduct = Product::query()->where('sku', 'WRK-2001')->first();

            if ($reviewProduct !== null) {
                $order = Order::query()->updateOrCreate(
                    ['order_number' => 'ORD-DEMO-1001'],
                    [
                        'user_id' => $customerUser->id,
                        'customer_name' => $customerUser->name,
                        'customer_email' => $customerUser->email,
                        'customer_phone' => $customerUser->phone ?? '+90 555 000 0002',
                        'shipping_address' => $customerUser->address_line ?? 'Customer Avenue 12',
                        'city' => $customerUser->city ?? 'Istanbul',
                        'postal_code' => $customerUser->postal_code ?? '34000',
                        'notes' => 'Seeded demo order',
                        'subtotal' => $reviewProduct->price,
                        'shipping_amount' => 0,
                        'total_amount' => $reviewProduct->price,
                        'status' => 'delivered',
                    ],
                );

                $order->orderItems()->updateOrCreate(
                    ['product_id' => $reviewProduct->id],
                    [
                        'product_name' => $reviewProduct->name,
                        'sku' => $reviewProduct->sku,
                        'price' => $reviewProduct->price,
                        'quantity' => 1,
                        'line_total' => $reviewProduct->price,
                    ],
                );

                Review::query()->updateOrCreate(
                    [
                        'user_id' => $customerUser->id,
                        'product_id' => $reviewProduct->id,
                    ],
                    [
                        'order_id' => $order->id,
                        'rating' => 5,
                        'title' => 'Excellent daily-use headphones',
                        'body' => 'Comfortable fit, reliable battery life, and a clean sound profile for both work and travel.',
                        'status' => 'approved',
                        'approved_at' => now(),
                    ],
                );

                $reviewProduct->syncReviewMetrics();
            }

            if ($wishlistProduct !== null) {
                Wishlist::query()->updateOrCreate([
                    'user_id' => $customerUser->id,
                    'product_id' => $wishlistProduct->id,
                ]);
            }
        }
    }

    private function photo(string $photoId): string
    {
        return "https://images.unsplash.com/{$photoId}?auto=format&fit=crop&w=1000&q=82";
    }
}
