<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_seeded_products(): void
    {
        $this->seed();

        $product = Product::query()->firstOrFail();
        $category = Category::query()->firstOrFail();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Luna Commerce')
            ->assertSee($product->name)
            ->assertSee($category->image_url);
    }

    public function test_product_page_displays_product_photo(): void
    {
        $this->seed();

        $product = Product::query()->firstOrFail();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee($product->image_url);
    }

    public function test_products_page_can_filter_by_category(): void
    {
        $this->seed();

        $audioCategory = Category::query()->where('slug', 'audio')->firstOrFail();
        $audioProduct = Product::query()->whereBelongsTo($audioCategory)->firstOrFail();
        $otherProduct = Product::query()->where('category_id', '!=', $audioCategory->id)->firstOrFail();

        $this->get(route('products.index', ['category' => $audioCategory->slug]))
            ->assertOk()
            ->assertSee($audioProduct->name)
            ->assertDontSee($otherProduct->name);
    }

    public function test_checkout_creates_order_and_line_items(): void
    {
        $this->seed();

        $product = Product::query()->firstOrFail();

        $cart = [
            $product->id => [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'image_url' => $product->image_url,
                'quantity' => 2,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])->post(route('checkout.store'), [
            'customer_name' => 'Ada Lovelace',
            'customer_email' => 'ada@example.com',
            'customer_phone' => '+1 555 100 200',
            'shipping_address' => '123 Logic Street',
            'city' => 'Istanbul',
            'postal_code' => '34000',
            'notes' => 'Leave at reception.',
        ]);

        $order = Order::query()->first();

        $response->assertRedirect(route('checkout.success', $order));

        $this->assertNotNull($order);
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'ada@example.com',
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_name' => $product->name,
            'quantity' => 2,
        ]);
    }
}
