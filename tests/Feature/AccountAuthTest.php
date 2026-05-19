<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_account_page(): void
    {
        $this->get(route('account.show'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_register_and_is_logged_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Grace Hopper',
            'email' => 'grace@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('account.show'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'grace@example.com',
            'name' => 'Grace Hopper',
        ]);
    }

    public function test_user_can_log_in_with_existing_account(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('account.show'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_update_personal_information(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('account.update'), [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'phone' => '+90 555 000 0000',
            'address_line' => 'Istanbul Tech Avenue 7',
            'city' => 'Istanbul',
            'postal_code' => '34000',
            'country' => 'Turkey',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'phone' => '+90 555 000 0000',
            'country' => 'Turkey',
        ]);
    }

    public function test_user_can_save_a_masked_payment_card(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('account.cards.store'), [
            'cardholder_name' => 'Updated User',
            'card_number' => '4242 4242 4242 4242',
            'expiry_month' => 12,
            'expiry_year' => now()->year + 2,
            'is_default' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('saved_cards', [
            'user_id' => $user->id,
            'brand' => 'Visa',
            'last_four' => '4242',
            'is_default' => true,
        ]);
    }
}
