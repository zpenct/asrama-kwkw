<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Root',
            'email' => 'superadmin-asrama@gmail.com',
            'password' => bcrypt('rahasia123!'),
            'is_first' => 0,
            'role' => 'SUPERADMIN',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin-asrama@gmail.com',
            'password' => bcrypt('rahasia123!'),
            'is_first' => 0,
            'role' => 'ADMIN',
        ]);
    }
}
