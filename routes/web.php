<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::get('/', function () {
    return view('welcome');
});

// ダッシュボード (1つにまとめました)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// プロフィール設定 (Breeze標準)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* * アプリ独自機能 
 * まとめて middleware(['auth', 'verified']) をかけることで、
 * ログインしていない人は使えないようにブロックします。
 */
Route::middleware(['auth', 'verified'])->group(function () {
    
    // スケジュール画面
    Route::get('/schedule', function () {
        return view('schedule');
    })->name('schedule');

    // AIチャット画面 (旧)
    Route::get('/ai-chat', function () {
        return view('ai-chat');
    })->name('ai-chat');

    // Gemini AI機能 (新)
    Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
    Route::post('/gemini', [GeminiController::class, 'post'])->name('gemini.post');
});
    Route::get('/index', [GeminiController::class, 'index']);
    Route::post('/index', [GeminiController::class, 'post']);

// ▼ 正しいモデル確認用コード（修正済み） ▼
use Gemini\Laravel\Facades\Gemini;

Route::get('/debug-gemini', function () {
    try {
        // ★ここを修正しました: listModels() ではなく models()->list() が正解です
        $response = Gemini::models()->list();
        
        // 使えるモデルの名前を一覧にして表示します
        $availableModels = collect($response->models)
            ->filter(fn($m) => in_array('generateContent', $m->supportedGenerationMethods))
            ->map(fn($m) => $m->name)
            ->values();

        return $availableModels;

    } catch (\Throwable $e) {
        return "エラーが発生しました: " . $e->getMessage();
    }
});
require __DIR__.'/auth.php';