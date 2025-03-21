<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BuildingController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


Route::get('/', [WelcomeController::class, 'index']);
Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    $status = Password::sendResetLink(request()->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'is_first' => 0,
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('filament.admin.auth.login')->with('status', __($status)) // 🔹 Perbaiki nama route login
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

Route::get('/reset-password-send', function () {
    return view('auth.reset-password-send');
})->name('password.send');