<?php

use App\Http\Controllers\PhotoController;
use Inertia\Inertia;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
//admin rotues
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('/photos', PhotoController::class);
});
Route::get('/photos', function () {
    return Inertia::render('Guest/Photos', [
        'photos' => Photo::all(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
});
