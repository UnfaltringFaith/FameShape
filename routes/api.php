<?php

use App\Http\Controllers\UserController;
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
    Route::post('/{post}/like', [App\Http\Controllers\PostController::class, 'like'])
        ->name('posts.like');
    Route::post('/{post}/dislike', [App\Http\Controllers\PostController::class, 'dislike'])
        ->name('posts.dislike');

    Route::post('/', [App\Http\Controllers\PostController::class, 'store'])
        ->name('posts.store');
    Route::put('/{post}', [App\Http\Controllers\PostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/{post}', [App\Http\Controllers\PostController::class, 'destroy'])
        ->name('posts.destroy');

    // Comments
    Route::post('/{post}/comments', [App\Http\Controllers\PostCommentsController::class, 'store'])
        ->name('comments.store');
    Route::get('/{post}/comments', [App\Http\Controllers\PostCommentsController::class, 'index'])
        ->name('comments.index');
});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])
        ->name('user.index');
    Route::put('/{comment}', [UserController::class, 'update'])
        ->name('comments.update');
    Route::delete('/{comment}', [UserController::class, 'destroy'])
        ->name('comments.destroy');
});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])
        ->name('user.index');
});

Route::prefix('knowledge_base')->group(function () {
    Route::get('/muscle_groups', [App\Http\Controllers\MuscleGroupController::class, 'index'])
        ->name('muscle_groups.index');
});

Route::get('/tags', function () {
    $tags = \App\Models\Tag::all('id', 'name');
    return response()->json($tags);
})->name('posts.tags');
