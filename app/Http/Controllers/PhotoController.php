<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Requests\PhotoRequest;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Admin/Photos', [
            'photos' => Photo::orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/PhotosCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhotoRequest $request)
    {
        $validated_data = $request->validated();
        // dd($validated_data);
        $path = Storage::disk('public')->put('photos', $request->file('path'));
        $validated_data['path'] = "/storage/$path";
        Photo::create($validated_data);
        return to_route('admin.photos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        return Inertia::render('Admin/PhotosEdit', [
            'photo' => $photo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(PhotoRequest $request, Photo $photo)
    {
        if ($request->hasFile('path')) {
            //delete old photo
            Storage::delete($photo->path);
            //add new photo
            $validated_data = $request->validated();
            $path = Storage::disk('public')->put('photos', $request->file('path'));
            $validated_data['path'] =  "/storage/$path";
        }
        $photo->update($validated_data);
        return to_route('admin.photos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        Storage::delete($photo->path);
        $photo->delete();
        return to_route('admin.photos.index');
    }
}
