<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
}
