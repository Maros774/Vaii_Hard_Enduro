<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Statické stránky
Route::view('/contact', 'contact')->name('contact');
Route::view('/motorcycles', 'motorcycles')->name('motorcycles');

// Dynamické CRUD operácie pre príspevky
Route::resource('posts', PostController::class);
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');

// Autentifikácia
Auth::routes();

// Chránené stránky
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

// Verejné stránky
Route::get('/about', [AboutController::class, 'index'])->name('about');


Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');

Route::post('/posts/{post}/like', [PostController::class, 'like']);
