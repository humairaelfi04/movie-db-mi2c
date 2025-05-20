<?php

namespace App\Http\Controllers;

use App\Models\Movie;
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
        $movie = Movie::findOrFail($id);
        return view('show', compact('movie'));
    }
}
