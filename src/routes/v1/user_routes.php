<?php

use App\Http\Controllers\API\v1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')->group(function () {
    Route::post('/leaderboards', [UserController::class, 'leaderboards'])->name('user.leaderboards');
});