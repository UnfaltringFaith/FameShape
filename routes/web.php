<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');


require __DIR__.'/auth.php';
