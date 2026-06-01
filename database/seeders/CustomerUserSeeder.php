<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::query()->updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('12345678'),
                'phone' => '+90 555 000 0002',
                'address_line' => 'Customer Avenue 12',
                'city' => 'Istanbul',
                'postal_code' => '34000',
                'country' => 'Turkey',
            ],
        );

        $userRole = Role::query()->where('name', 'user')->value('id');

        if ($userRole !== null) {
            $customer->roles()->syncWithoutDetaching([$userRole]);
        }
    }
}
