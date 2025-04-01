<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::whereIn('name', [
            'view_building',
            'view_any_building',
            'create_building',
            'update_building',
            'delete_building',
            'delete_any_building',

            'view_facility',
            'view_any_facility',
            'create_facility',
            'update_facility',
            'delete_facility',
            'delete_any_facility',

            'view_room',
            'view_any_room',
            'create_room',
            'update_room',
            'delete_room',
            'delete_any_room',
        ])->get();

        // Cari atau buat role "admin"
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Assign hanya permission terkait Role dan User ke Admin
        $role->syncPermissions($permissions);
    }
}
