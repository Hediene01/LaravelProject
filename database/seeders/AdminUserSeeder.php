<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'phone' => '+90 555 000 0001',
                'address_line' => 'Admin Street 1',
                'city' => 'Istanbul',
                'postal_code' => '34000',
                'country' => 'Turkey',
            ],
        );

        $roleIds = Role::query()
            ->whereIn('name', ['admin', 'user'])
            ->pluck('id')
            ->all();

        $adminUser->roles()->syncWithoutDetaching($roleIds);
    }
}
