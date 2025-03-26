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
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123123'),
            ]
        );

        // Buat atau ambil role super-admin
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Ambil semua permission yang sudah dibuat oleh Filament Shield
        $permissions = Permission::all();

        // Assign semua permission ke role
        $role->syncPermissions($permissions);

        // Assign role super-admin ke user
        $user->assignRole($role);
    }
}
