<?php

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
        return inertia('Admin/PhotosCreate');
    })->name('photos.create');
    Route::post('/photos', function (Request $request) {
        // dd('i will handle form submission');
        $validated_data = $request->validate([
            'path' => ['image','required','max:2500'],
            'description' => ['required']
        ]);
        $path = Storage::disk('public')->put('photos', $request->file('path'));
        $validated_data['path'] = "/storage/$path";
        Photo::create($validated_data);
        return to_route('admin.photos');
        // dd($path);
    })->name('photos.store');
});
Route::get('/photos', function () {
    // dd(Photo::all());
    return Inertia::render('Guest/Photos', [
        'photos' => Photo::all(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
});
