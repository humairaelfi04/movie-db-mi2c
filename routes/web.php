<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [MovieController::class, 'homepage']);
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// Route::get('/movies/{id}/{slug}', [MovieController::class, 'detail_movie']);

Route::get('/create-movies', [MovieController::class, 'create'])->name('movies.create')->middleware('auth');

Route::post('/movie/store', [MovieController::class, 'store'])->name('movies.store')->middleware('auth');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);
