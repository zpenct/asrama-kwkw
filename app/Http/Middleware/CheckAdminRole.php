<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/login')) {
            return $next($request);
        }

        if (! Auth::check()) {
            return $next($request);
        }

        // Cek role user yang sudah login
        $user = Auth::user();

        if (! in_array(($user->role), ['ADMIN', 'SUPERADMIN'])) {
            return redirect('/')->with('status', 'Silakan cek email Anda untuk reset password.');
        }

        return $next($request);
    }
}
