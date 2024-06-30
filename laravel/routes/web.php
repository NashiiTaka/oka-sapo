<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);
Route::get('/chat/{message}', [ChatController::class, 'chat']);
Route::post('/passer', [ChatController::class, 'passer']);
