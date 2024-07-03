<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FaceDetectionController;
use App\Http\Middleware\BeforeChatMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);

// localhost/chat/ask-message という様なアクセスがあると、
// {message}のところが、ask-messageになっている。
// そうすると、ChatControllerのchatメソッドが呼び出される際に、
// $messageに'ask-message'が入る。
Route::get('/chat/{message}', [ChatController::class, 'chat'])->middleware(BeforeChatMiddleware::class);
Route::post('/passer', [ChatController::class, 'passer']);
Route::get('/osusume/{from}', [ChatController::class, 'osusume']);
Route::get('/osusume/{from}/{colorCode}', [ChatController::class, 'osusume']);
Route::get('/form', [ChatController::class, 'form']); //打ち込み型のルート設定
Route::post('/multiple', [ChatController::class, 'multiple']); //複数選択のルート

Route::get('/face-detection', [FaceDetectionController::class, 'index'])->name('face-detection.index');
Route::get('/face-detection/{product_id}', [FaceDetectionController::class, 'withProduct'])->name('face-detection.with-product');

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