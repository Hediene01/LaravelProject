<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $role = Role::query()->create([
            'name' => 'user',
            'description' => 'Standard customer role',
        ]);

        $user = User::factory()->create();
        $user->roles()->attach($role);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Store dashboard');
    }

    public function test_admin_user_can_create_a_product_from_the_admin_panel(): void
    {
        $admin = $this->createAdminUser();
        $category = Category::query()->create([
            'name' => 'Audio',
            'slug' => 'audio',
            'description' => 'Audio devices',
            'image_url' => 'https://example.com/category.jpg',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.product.store'), [
            'category_id' => $category->id,
            'name' => 'Studio Headphones',
            'keywords' => 'audio, studio',
            'description' => 'A short description for the product.',
            'detail' => 'Longer product detail content for the admin CRUD example.',
            'sku' => 'ADM-1001',
            'price' => '125.00',
            'compare_price' => '149.00',
            'inventory' => 12,
            'min_stock' => 3,
            'discount' => 10,
            'image_url' => 'https://example.com/headphones.jpg',
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.product.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Studio Headphones',
            'sku' => 'ADM-1001',
            'user_id' => $admin->id,
            'is_active' => true,
        ]);
    }

    private function createAdminUser(): User
    {
        $adminRole = Role::query()->create([
            'name' => 'admin',
            'description' => 'Administrator',
        ]);

        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        return $user;
    }
}
