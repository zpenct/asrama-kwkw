<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123123'),
                'is_first' => false,
            ]
        );

        // Ambil hanya permission untuk Role & User dari Filament Shield
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

        // Assign role super-admin ke user
        $user->assignRole($role);
    }
}
