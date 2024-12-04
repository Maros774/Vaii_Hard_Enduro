<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Statické stránky
Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/motorcycles', 'motorcycles');

// Dynamické CRUD operácie
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class);
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');

// Domovská stránka
Route::get('/', function () {
    return view('home');
})->name('home');

// Autentifikácia
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/posts/search', [PostController::class, 'search']);

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

use App\Http\Controllers\AboutController;

Route::get('/about', [AboutController::class, 'index'])->name('about');

// Chránené stránky
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

// Verejné stránky
Route::get('/', function () {
    return view('home');
})->name('home');
