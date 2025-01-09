<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MotorcycleController;

// Hlavná stránka
Route::get('/', [HomeController::class, 'index'])->name('home');

// Statické stránky
Route::view('/contact', 'contact')->name('contact');

// Dynamické CRUD operácie pre príspevky
Route::resource('posts', PostController::class);
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');

// Autentifikácia
Auth::routes();

// Chránené stránky (dashboard pre prihlásených používateľov)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

// Verejné stránky - O nás (pre všetkých)
Route::get('/about', [AboutController::class, 'index'])->name('about.index');


// Adminské operácie pre sekciu "O nás"
Route::middleware('admin')->prefix('about')->group(function () {
    Route::get('/create', [AboutController::class, 'create'])->name('about.create');
    Route::post('/', [AboutController::class, 'store'])->name('about.store');
    Route::get('/{about}/edit', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('/{about}', [AboutController::class, 'update'])->name('about.update');
    Route::delete('/{about}', [AboutController::class, 'destroy'])->name('about.destroy');
});

// Adminské operácie pre sekciu "Motocykle"
Route::resource('motorcycles', MotorcycleController::class);
