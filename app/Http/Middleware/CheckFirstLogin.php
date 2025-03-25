<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class CheckFirstLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Jika user sudah login dan is_first = 1, kirim email reset password lalu redirect
        if ($user && $user->is_first == 1) {
            Password::sendResetLink(['email' => $user->email]); // Kirim email reset password

            return redirect('/reset-password-send')->with('status', 'Silakan cek email Anda untuk reset password.');
        }

        return $next($request);
    }
}
