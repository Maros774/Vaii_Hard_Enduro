<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MotorcycleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Domovská stránka
Route::get('/', [HomeController::class, 'index'])->name('home');

// Kontakt (statická stránka)
Route::view('/contact', 'contact')->name('contact');

Route::resource('posts', PostController::class);

// Ďalšie osobitné akcie chránené prihlásením
Route::middleware('auth')->group(function () {
    // Lajkovanie
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');

    // Komentáre
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Verejná stránka O nás
Route::get('/about', [AboutController::class, 'index'])->name('about.index');

// Admin prefix pre O nás
Route::middleware('admin')->prefix('about')->group(function () {
    Route::get('/create', [AboutController::class, 'create'])->name('about.create');
    Route::post('/', [AboutController::class, 'store'])->name('about.store');
    Route::get('/{about}/edit', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('/{about}', [AboutController::class, 'update'])->name('about.update');
    Route::delete('/{about}', [AboutController::class, 'destroy'])->name('about.destroy');
});

// Motocykle
Route::resource('motorcycles', MotorcycleController::class);

// Autentifikácia (login, register atď.)
Auth::routes();

// Dashboard (napr. pre prihlásených)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});
