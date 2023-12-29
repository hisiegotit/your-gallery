<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function create()
    {
        return view('albums.create');
    }

    public function store(Request $request)
    {
        $imageName = time() . '.' . $request->image->hashName();

        $request->image->move(public_path('albums'), $imageName);

        Album::created([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
            'slug' => Str::slug($request),
            'image' => $imageName,
        ]);

        $id = $album->id;
        return response()->json(['id' => $id]);
    }
}
