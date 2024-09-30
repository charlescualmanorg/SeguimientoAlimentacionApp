<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/feed', 'HomeController@index')->name('feed');

Route::middleware(['auth'])->group(function() {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('posts.like');
    Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    // Rutas para comentarios
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    // Rutas para likes
    Route::post('/posts/{post}/like', [LikeController::class, 'toggleLike'])->name('posts.like');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::get('/profile', 'ProfileController@show')->name('profile.show');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
});