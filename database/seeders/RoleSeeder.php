<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'admin', 'description' => 'Can access the admin dashboard and manage store content.'],
            ['name' => 'user', 'description' => 'Standard customer account role.'],
            ['name' => 'editor', 'description' => 'Can edit content when granted by the application.'],
            ['name' => 'moderator', 'description' => 'Can review and moderate restricted sections.'],
        ] as $role) {
            Role::query()->updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']],
            );
        }
    }
}
