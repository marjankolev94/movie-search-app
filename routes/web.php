<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MovieController::class, 'index'])->name('movies.index');

Route::get('/search-movies', [MovieController::class, 'searchMovies'])->name('search.movies');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');
