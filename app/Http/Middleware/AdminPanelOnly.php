<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelOnly
{
    public function handle(Request $request, Closure $next): Response
    {

        $currentPath = $request->path();

        // Allow access to admin login and logout routes without role check
        if (in_array($currentPath, ['admin/login', 'admin/logout'])) {
            return $next($request);
        }

        $user = $request->user();

        logger()->info('ROLE CHECK:', [
            'user_id' => $user?->id,
            'roles' => $user?->getRoleNames(),
        ]);

        if (! $user || ! $user->hasAnyRole(['admin', 'superadmin', 'super_admin'])) {
            abort(403, 'Akses hanya untuk Admin.');
        }

        return $next($request);
    }
}
