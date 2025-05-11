<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = $request->user();

        // Debug sementara
        logger()->info('ROLE CHECK:', [
            'user_id' => $user?->id,
            'role' => $user?->role,
        ]);

        if ($user && $user->hasAnyRole(['user'])) {
            return redirect()->to('/');
        }

        return parent::toResponse($request);
    }
}
