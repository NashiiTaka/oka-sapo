<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);

// localhost/chat/ask-message という様なアクセスがあると、
// {message}のところが、ask-messageになっている。
// そうすると、ChatControllerのchatメソッドが呼び出される際に、
// $messageに'ask-message'が入る。
Route::get('/chat/{message}', [ChatController::class, 'chat']);
Route::post('/passer', [ChatController::class, 'passer']);
Route::get('/osusume', [ChatController::class, 'osusume']);

// ルーティングのグループ化
// グループ化する接頭辞
Route::prefix('admin')
    // nameを共通化
    ->name('admin.')
    // 担当コントローラーを共通化
    ->controller(AdminController::class)
    // 実際のルーティング処理、共通化したものは省く。
    ->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('check-colors', 'checkColors')->name('check-colors');
    });