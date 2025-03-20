<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BuildingController;


Route::get('/', [WelcomeController::class, 'index']);
Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show');