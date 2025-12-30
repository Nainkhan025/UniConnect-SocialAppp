<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostInteractionController;

use Illuminate\Support\Facades\Route;


Route::middleware(['auth' , 'admin'])->group(function(){
  Route::get('/admin' , [AdminController::class, 'dashboard'])->name('admin.board');
});

Route::get('/', [PostController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');



});

Route::middleware(['auth' , 'approved'])->group(function(){

    Route::get('/posts' , [PostController::class , 'index'])->name('posts.index');
    Route::post('/posts' , [PostController::class , 'store'])->name('posts.store');
     Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

});


Route::middleware('auth')->group(function () {
    // Post interactions
    Route::post('/posts/{post}/like', [PostInteractionController::class, 'like']);
    Route::get('/posts/{post}/comments', [PostInteractionController::class, 'comments']);
    Route::get('/posts/{post}/likers', [PostInteractionController::class, 'likers']);
    Route::post('/posts/{post}/comment', [PostInteractionController::class, 'comment']);
    Route::delete('/comments/{comment}', [PostInteractionController::class, 'deleteComment']);
});

require __DIR__.'/auth.php';