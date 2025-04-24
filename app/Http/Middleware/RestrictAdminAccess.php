<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();

        // Debug sementara
        logger()->info('ROLE CHECK:', [
            'user_id' => $user?->id,
            'role' => $user?->role,
        ]);

        if ($user && $user->hasAnyRole(['admin', 'superadmin', 'super_admin'])) {
            abort(403, 'Akses hanya untuk pengguna non-admin.');
        }

        return $next($request);
    }
}
