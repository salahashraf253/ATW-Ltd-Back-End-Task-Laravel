<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;



Route::get('/stats', [StatsController::class, 'index']);


Route::middleware('auth:sanctum')->group(function (){
    // Tags API resource
    Route::get('/Tags', [TagController::class, 'index']);
    Route::post('/Tags', [TagController::class, 'store']);
    Route::put('/Tags/{tag}', [TagController::class, 'update']);
    Route::delete('/Tags/{tag}', [TagController::class, 'destroy']);

    // Posts API resource
    Route::get('/Posts', [PostController::class, 'index']);
    Route::post('/Posts',  [PostController::class, 'store']);
    Route::get('/Posts/{post}',  [PostController::class, 'show']);
    Route::put('/Posts/{post}', [PostController::class, 'update']);
    Route::delete('/Posts/{post}', [PostController::class, 'destroy']);

    Route::get('/Posts/deleted', [PostController::class, 'deleted']);

    
    Route::patch('/Posts/{post}/restore',[PostController::class, 'restore']);

});
