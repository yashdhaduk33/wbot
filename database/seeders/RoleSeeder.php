<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Has all permissions',
                'permissions' => [
                    'user.view', 'user.create', 'user.edit', 'user.delete',
                    'role.view', 'role.create', 'role.edit', 'role.delete',
                    'department.view', 'department.create', 'department.edit', 'department.delete',
                    'whatsapp_bot.view', 'whatsapp_bot.create', 'whatsapp_bot.edit', 'whatsapp_bot.delete', 'whatsapp_bot.manage'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with limited permissions',
                'permissions' => [
                    'user.view', 'user.create', 'user.edit',
                    'department.view', 'department.create', 'department.edit',
                    'whatsapp_bot.view', 'whatsapp_bot.create', 'whatsapp_bot.edit'
                ],
                'is_active' => true
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular user',
                'permissions' => ['user.view', 'whatsapp_bot.view'],
                'is_active' => true
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}