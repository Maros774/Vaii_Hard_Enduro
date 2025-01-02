<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;

// Statické stránky
Route::view('/contact', 'contact')->name('contact');
Route::view('/motorcycles', 'motorcycles')->name('motorcycles');

// Dynamické CRUD operácie pre príspevky
Route::resource('posts', PostController::class)
    ->except(['show']); // Metóda 'show' sa spravuje samostatne

// Vyhľadávanie
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Domovská stránka
Route::get('/', function () {
    return view('home');
})->name('home');

// Autentifikácia
Auth::routes();

// Chránené stránky
Route::middleware(['auth'])->group(function () {
    // Dashboard pre autentifikovaných používateľov
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Verejné stránky
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/', [PostController::class, 'index'])->name('home');
Route::resource('posts', PostController::class);
