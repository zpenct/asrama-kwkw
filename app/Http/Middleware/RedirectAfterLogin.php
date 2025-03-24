<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Jika sudah di dalam panel admin, biarkan akses tetap berjalan
        if ($request->is('admin') || $request->is('admin/*')) {
            if ($user->hasRole(['super_admin', 'admin'])) {
                return $next($request);
            } else {
                return redirect('/'); // User biasa tidak boleh akses admin
            }
        }

        // Redirect berdasarkan role
        if ($user->hasRole(['super_admin', 'admin'])) {
            return redirect('/admin');
        }

        return redirect('/');
    }
}
