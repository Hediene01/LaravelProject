<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_api_supports_brand_filter(): void
    {
        $this->seed();

        $brand = Brand::query()->firstOrFail();

        $response = $this->getJson('/api/products?brand='.$brand->slug);

        $response->assertOk();
        $response->assertJsonPath('data.0.brand.slug', $brand->slug);
    }

    public function test_products_api_supports_search_query(): void
    {
        $this->seed();

        $product = Product::query()->firstOrFail();

        $response = $this->getJson('/api/products?q='.urlencode($product->name));

        $response->assertOk();
        $response->assertJsonFragment(['slug' => $product->slug]);
    }

    public function test_featured_products_api_returns_items(): void
    {
        $this->seed();

        $this->getJson('/api/products/featured')
            ->assertOk()
            ->assertJsonCount(5);
    }
}
