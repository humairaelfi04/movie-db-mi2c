<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MovieController extends Controller
{
    public function homepage()
    {
        $movies = Movie::latest()->paginate(6);
        return view('homepage', compact('movies'));
    }

    public function show($id)
    {
        $movie = Movie::with('category')->findOrFail($id);
        return view('show', compact('movie'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('create-movie', compact('categories'));
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'synopsis' => 'nullable',
        'category_id' => 'required|exists:categories,id',
        'year' => 'required|digits:4|integer',
        'actors' => 'nullable',
        'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Upload cover image
    $coverName = null;
    if ($request->hasFile('cover')) {
        $coverName = time() . '.' . $request->cover->extension();
        $request->cover->move(public_path('covers'), $coverName);
    }

    // Simpan data ke database
    Movie::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'synopsis' => $request->synopsis,
        'category_id' => $request->category_id,
        'year' => $request->year,
        'actors' => $request->actors,
        'cover_image' => $coverName,
    ]);

    return redirect('/')->with('success', 'Movie berhasil ditambahkan!');
}

    public function edit($id)
{
    $movie = Movie::findOrFail($id);
    $categories = Category::all();
    return view('edit-movie', compact('movie', 'categories'));
}

public function update(Request $request, $id)
{
    $movie = Movie::findOrFail($id);


    $request->validate([
        'title' => 'required|max:255',
        'synopsis' => 'nullable',
        'category_id' => 'required|exists:categories,id',
        'year' => 'required|digits:4|integer',
        'actors' => 'nullable',
        'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // update cover
    if ($request->hasFile('cover')) {
        $coverName = time() . '.' . $request->cover->extension();
        $request->cover->move(public_path('covers'), $coverName);
        $movie->cover_image = $coverName;
    }

    $movie->update([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'synopsis' => $request->synopsis,
        'category_id' => $request->category_id,
        'year' => $request->year,
        'actors' => $request->actors,
        'cover_image' => $movie->cover_image, // jika tetap pake cover lama
    ]);

    return redirect('/')->with('success', 'Movie berhasil diperbarui!');
}

public function destroy($id)
{
    if(Gate::allows('delete')){
        $movie = Movie::findOrFail($id);
        $movie->delete();

         return redirect('/')->with('success', 'Movie berhasil dihapus!');
    }else{
        abort(403);
    }


    // // hapus cover
    // if ($movie->cover_image && file_exists(public_path('covers/' . $movie->cover_image))) {
    //     unlink(public_path('covers/' . $movie->cover_image));
    // }

}

}
