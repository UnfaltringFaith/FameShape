<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])
        ->name('profile.edit');
});

Route::middleware('auth:sanctum')->prefix('posts')->group(function () {
    Route::get('/', [App\Http\Controllers\PostController::class, 'index'])
        ->name('posts.index');
    Route::get('/user_posts', [App\Http\Controllers\PostController::class, 'user_posts'])
        ->name('posts.user_posts');
    Route::get('/{post}', [App\Http\Controllers\PostController::class, 'show'])
        ->name('posts.show')
        ->middleware('can:view,post');
    Route::post('/', [App\Http\Controllers\PostController::class, 'store'])
        ->name('posts.store');
    Route::put('/{post}', [App\Http\Controllers\PostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/{post}', [App\Http\Controllers\PostController::class, 'destroy'])
        ->name('posts.destroy');
});

Route::middleware('auth:sanctum')->prefix('account')->group(function(){
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])
        ->name('account.index');
});