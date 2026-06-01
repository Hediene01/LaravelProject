<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_toggle_wishlist(): void
    {
        $this->seed();

        $user = User::query()->where('email', 'customer@example.com')->firstOrFail();
        $product = Product::query()->firstOrFail();

        $this->actingAs($user)
            ->post(route('wishlist.toggle', $product))
            ->assertRedirect();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_purchasing_user_can_submit_review(): void
    {
        $this->seed();

        $user = User::query()->where('email', 'customer@example.com')->firstOrFail();
        $product = Product::query()->where('sku', 'AUD-1001')->firstOrFail();

        $this->actingAs($user)
            ->post(route('reviews.store', $product), [
                'rating' => 4,
                'title' => 'Updated review',
                'body' => 'This is still a very strong product after a full week of use.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => 'pending',
        ]);
    }

    public function test_checkout_attaches_order_to_authenticated_user(): void
    {
        $this->seed();

        $user = User::query()->where('email', 'customer@example.com')->firstOrFail();
        $product = Product::query()->firstOrFail();

        $cart = [
            $product->id => [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'image_url' => $product->image_url,
                'quantity' => 1,
            ],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post(route('checkout.store'), [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'shipping_address' => $user->address_line,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'notes' => 'Authenticated checkout test.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'customer_email' => $user->email,
            'user_id' => $user->id,
        ]);
    }
}
