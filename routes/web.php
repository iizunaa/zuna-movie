<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LikedController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardFilmController;
use App\Http\Controllers\DashboardGenreController;

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

Route::get('/login', function () {
    return view('Login');
});

Route::get('/register', function () {
    return view('Register');
});

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/dashboard', function() {
    return view('dashboard.index');
})->middleware('admin');

Route::resource('/dashboard/films', DashboardFilmController::class)->middleware('admin');
Route::resource('/dashboard/genres', DashboardGenreController::class)->middleware('admin');

Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth')->group(function(){
    Route::get('/home/{film}', [HomeController::class, 'show'])->name('film.show');
    Route::get('/history', [HistoryController::class, 'index'])->middleware('auth')->name('history.index');
    Route::post('/home/{film}/rate', [HomeController::class, 'rateFilm'])->middleware('auth')->name('film.rate');
    Route::get('/liked', [LikedController::class, 'index'])->name('liked.index')->middleware('auth');
});