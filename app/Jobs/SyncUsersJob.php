<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class SyncUsersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
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
                ]
            );

            // Assign Role using Spatie
            $role = Role::firstOrCreate(['name' => 'user']);
            if (! $user->hasRole('user')) {
                $user->assignRole($role);
                Log::channel('sync')->info("Assigned role 'user' to user {$user->email}");
                Log::info("Assigned role 'user' to user {$user->email}");
            }
        }
    }
}
