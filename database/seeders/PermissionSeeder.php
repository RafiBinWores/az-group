<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-production-report',
            'create-production-report',
            'edit-production-report',
            'delete-production-report',
            'view-finishing-report',
            'create-finishing-report',
            'edit-finishing-report',
            'delete-finishing-report',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
