<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Password;

class SendResetPasswordEmail
{
    public function handle(Login $event)
    {
        $user = $event->user;

        // if ($user->is_first == 1) {
        //     // Kirim email reset password ke user
        //     Password::sendResetLink(['email' => $user->email]);

        //     // Update is_first jadi 0 setelah email dikirim
        //     $user->update(['is_first' => 0]);
        // }
    }
}
