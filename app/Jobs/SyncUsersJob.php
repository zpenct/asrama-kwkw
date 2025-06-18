<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SyncUsersJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        logger()->info('[SyncUsersJob] Mulai mengambil data dari master_db...');

        // Ambil semua email yang sudah ada di tabel users
        $existingEmails = User::pluck('email')->toArray();

        // Ambil hanya mahasiswa aktif yang belum ada di users
        $students = DB::connection('master_db')
            ->table('students')
            ->where('status', 'active')
            ->whereNotIn('email_student', $existingEmails)
            ->get();

        logger()->info('[SyncUsersJob] Total mahasiswa aktif yang belum disinkron: '.$students->count());

        foreach ($students as $student) {
            $user = User::updateOrCreate(
                ['email' => $student->email_student],
                [
                    'name' => $student->full_name,
                    'password' => bcrypt($student->student_number),
                    'is_first' => false,
                    'gender' => $student->gender,
                ]
            );

            $role = Role::firstOrCreate(['name' => 'user']);
            $user->assignRole($role);

            logger()->info('[SyncUsersJob] User sinkron:', [
                'email' => $user->email,
                'id' => $user->id,
            ]);
        }

        logger()->info('[SyncUsersJob] Sinkronisasi selesai.');
    }
}
