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
        $students = DB::connection('master_db')
            ->table('students')
            ->where('status', 'active')
            ->get();

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

            // Assign Role using Spatie
            $role = Role::firstOrCreate(['name' => 'user']);
            $user->assignRole($role);

            logger()->info('CHECK For:', [
                'student' => $user,
            ]);

        }
    }
}
