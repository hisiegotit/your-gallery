<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function create()
    {
        return view('albums.create');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'string', 'max:20', 'min:3'],
            'description' => ['required', 'max:255', 'min:10'],
            'category_id' => ['required'],
            'image' => ['required', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        $imageName = time() . '.' . $request->image->hashName();

        $request->image->move(public_path('albums'), $imageName);

        $album = Album::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
            'slug' => Str::slug($request->name),
            'image' => $imageName,
        ]);

        $id = $album->id;
        return response()->json(['id' => $id]);
    }
}
