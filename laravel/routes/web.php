<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);
Route::post('/passer', [ChatController::class, 'passer']);
