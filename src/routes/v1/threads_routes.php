<?php

use App\Http\Controllers\API\v1\Thread\ThreadController;
use App\Http\Controllers\API\v1\Thread\AnswerController;
use App\Http\Controllers\API\v1\Thread\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::resource('threads', ThreadController::class);

Route::prefix('/threads')->group(function () {
    Route::resource('answers', AnswerController::class);

    Route::post('/{thread}/subscribe',[SubscribeController::class , 'subscribe'])->name('subscribe');
    Route::post('/{thread}/unSubscribe',[SubscribeController::class , 'unSubscribe'])->name('unSubscribe');
});



