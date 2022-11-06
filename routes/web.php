<?php

use Inertia\Inertia;
use App\Models\Photo;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

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
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/photos', function () {
        return inertia('Admin/Photos', [
            'photos' => Photo::all()
        ]);
    })->name('photos');

    Route::get('/photos/create', function () {
        return inertia('Admin/PhotosCreate')->name('photos.create');
    });
    Route::post('/photos', function () {
        dd('i will handle form submission');
    })->name('photos.store');
});
Route::get('photos', function () {
    // dd(Photo::all());
    return Inertia::render('Guest/Photos', [
        'photos' => Photo::all(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
});
