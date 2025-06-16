<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Middleware\RoleAdmin;
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

// edit
Route::get('/movies/{id}/edit', [MovieController::class, 'edit'])->name('movies.edit')->middleware('auth', RoleAdmin::class);

// update
Route::put('/movies/{id}', [MovieController::class, 'update'])->name('movies.update')->middleware('auth', RoleAdmin::class);

// delete
Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy')->middleware('auth');

