<?php

return [
    'list' => [
        'users' => [
            'view' => 'View Users',
            'create' => 'Create Users',
            'edit' => 'Edit Users',
            'delete' => 'Delete Users',
            'assign_roles' => 'Assign Roles to Users',
            'assign_departments' => 'Assign Departments to Users',
        ],
        'roles' => [
            'view' => 'View Roles',
            'create' => 'Create Roles',
            'edit' => 'Edit Roles',
            'delete' => 'Delete Roles',
            'assign_permissions' => 'Assign Permissions',
        ],
        'departments' => [
            'view' => 'View Departments',
            'create' => 'Create Departments',
            'edit' => 'Edit Departments',
            'delete' => 'Delete Departments',
            'assign_manager' => 'Assign Department Manager',
        ],
        'reports' => [
            'view' => 'View Reports',
            'generate' => 'Generate Reports',
            'export' => 'Export Reports',
        ],
        'settings' => [
            'view' => 'View Settings',
            'edit' => 'Edit Settings',
        ],
    ],

    'default_roles' => [
        'admin' => [
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full system access',
            'permissions' => ['*'],
            'is_default' => false,
        ],
        'manager' => [
            'name' => 'Department Manager',
            'slug' => 'manager',
            'description' => 'Department level access',
            'permissions' => [
                'users.view',
                'users.edit',
                'departments.view',
                'reports.view',
            ],
            'is_default' => false,
        ],
        'user' => [
            'name' => 'Regular User',
            'slug' => 'user',
            'description' => 'Basic user access',
            'permissions' => [
                'users.view',
            ],
            'is_default' => true,
        ],
    ],
];