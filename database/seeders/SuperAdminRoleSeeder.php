<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::whereIn('name', [
            'view_user',
            'view_any_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',

            'view_role',
            'view_any_role',
            'create_role',
            'update_role',
            'delete_role',
            'delete_any_role',
        ])->get();

        // Cari atau buat role "admin"
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Assign hanya permission terkait Role dan User ke Admin
        $role->syncPermissions($permissions);
    }
}
