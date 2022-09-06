<?php

use App\Http\Controllers\API\v1\Thread\ThreadController;
use Illuminate\Support\Facades\Route;

Route::resource('threads', 'API\v1\Thread\ThreadController');


//Route::resource('threads', ThreadController::class);
//Route::prefix('/thread')->group(function (){
//    Route::get('/index',[ThreadController::class,'index'])->name('thread.index');
//    Route::get('/show/{slug}',[ThreadController::class,'show'])->name('thread.show');
//});
