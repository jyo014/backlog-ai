<?php

use Illuminate\Support\Facades\Route;
use Gemini\Laravel\Facades\Gemini;

// ▼ コントローラーの読み込み
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\TaskController;    

//所々ごちゃってる

Route::get('/', function () {
    return view('welcome');
});

// 認証関連のルート読み込み
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| 認証済みユーザー専用ルート
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // 1. ダッシュボード
    // ==========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // ==========================================
    // 2. 日程管理 (Schedule)
    // ==========================================
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy'); // ★ここに移動して整理


    // ==========================================
    // 3. プロフィール設定
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ==========================================
    // 4. AIチャット & 個人タスク管理 (GeminiController)
    // ==========================================
    Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
    Route::post('/gemini', [GeminiController::class, 'post'])->name('gemini.post');
    
    // AIチャット画面での簡易タスク操作
    Route::post('/gemini/tasks', [GeminiController::class, 'storeTask'])->name('task.store'); // URLパスを少し区別推奨ですが、元のままでも可
    Route::patch('/gemini/tasks/{task}/complete', [GeminiController::class, 'completeTask'])->name('task.complete');
    Route::delete('/gemini/tasks/{task}', [GeminiController::class, 'deleteTask'])->name('task.delete');


    // ==========================================
    // 5. 大学授業・課題管理機能 (Wiki方式)
    // ==========================================
    // 授業一覧
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    // 新規登録
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    // 履修登録・解除
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/detach', [CourseController::class, 'detach'])->name('courses.detach');
    // 授業詳細・課題共有
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/tasks', [CourseController::class, 'storeTask'])->name('courses.tasks.store');


    // ==========================================
    // 6. チーム機能 (Team)
    // ==========================================
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team/join', [TeamController::class, 'join'])->name('team.join');
    Route::post('/team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/team/task', [TeamController::class, 'storeTeamTask'])->name('team.task.store');
    Route::post('/team/leave', [TeamController::class, 'leave'])->name('team.leave');


    // ==========================================
    // 7. 設定 (Settings)
    // ==========================================
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');


    // ==========================================
    // 8. 総合タスク管理 (TaskController)
    // ==========================================
    // 一覧表示
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    // ステータス更新
    Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    // Backlog取り込み
    Route::post('/tasks/import', [TaskController::class, 'importBacklog'])->name('tasks.import');


    // ==========================================
    // その他 (デバッグ等)
    // ==========================================
    Route::get('/ai-chat', function () { 
        return view('ai-chat'); 
    })->name('ai-chat');

    // Gemini API接続テスト用
    Route::get('/debug-gemini', function () {
        try {
            $response = Gemini::models()->list();
            return collect($response->models)
                ->filter(fn($m) => in_array('generateContent', $m->supportedGenerationMethods))
                ->map(fn($m) => $m->name)
                ->values();
        } catch (\Throwable $e) {
            return "エラーが発生しました: " . $e->getMessage();
        }
    });
    // ガントチャート表示
Route::get('/tasks/gantt', [TaskController::class, 'gantt'])->name('tasks.gantt');
});