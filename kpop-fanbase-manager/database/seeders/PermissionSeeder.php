<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Can create, edit, and delete users'],
            ['name' => 'Manage Groups', 'slug' => 'manage-groups', 'description' => 'Can create, edit, and delete groups'],
            ['name' => 'Manage Events', 'slug' => 'manage-events', 'description' => 'Can create, edit, and delete events'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'description' => 'Can assign permissions to users'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}