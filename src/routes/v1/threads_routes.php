<?php

use App\Http\Controllers\API\v1\Thread\ThreadController;
use App\Http\Controllers\API\v1\Thread\AnswerController;
use Illuminate\Support\Facades\Route;

Route::resource('threads', ThreadController::class);

Route::prefix('/threads')->group(function () {
    Route::resource('answers', AnswerController::class);
});



