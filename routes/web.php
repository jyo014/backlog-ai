<?php

use Illuminate\Support\Facades\Route;
use Gemini\Laravel\Facades\Gemini;

// ▼ コントローラー
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;


Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';


Route::middleware(['auth', 'verified'])->group(function () {

    // ==========================================
    // 1. ダッシュボード
    // ==========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // 2. 日程管理 (Schedule)
    // ==========================================
    Route::controller(ScheduleController::class)->prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::delete('/{schedule}', 'destroy')->name('destroy');
    });

    // ==========================================
    // 3. プロフィール設定
    // ==========================================
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // ==========================================
    // 4. AIチャット & 簡易タスク (Gemini)
    // ==========================================
    Route::controller(GeminiController::class)->prefix('gemini')->group(function () {
        // AIチャット
        Route::get('/', 'index')->name('gemini.index');
        Route::post('/', 'post')->name('gemini.post');

        // チャット画面でのタスク操作
        Route::post('/tasks', 'storeTask')->name('task.store');
        Route::patch('/tasks/{task}/complete', 'completeTask')->name('task.complete');
        Route::delete('/tasks/{task}', 'deleteTask')->name('task.delete');
    });

    // ==========================================
    // 5. 大学授業・課題管理 (Courses)
    // ==========================================
    Route::controller(CourseController::class)->prefix('courses')->name('courses.')->group(function () {
        // 基本操作
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{course}', 'show')->name('show');
        
        // 履修・課題
        Route::post('/{course}/enroll', 'enroll')->name('enroll');
        Route::delete('/{course}/detach', 'detach')->name('detach');
        Route::post('/{course}/tasks', 'storeTask')->name('tasks.store');
    });

    // ==========================================
    // 6. チーム機能 (Team)
    // ==========================================
    Route::controller(TeamController::class)->prefix('team')->name('team.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'create')->name('create');
        Route::post('/join', 'join')->name('join');
        Route::post('/leave', 'leave')->name('leave');
        Route::post('/task', 'storeTeamTask')->name('task.store');
    });

    // ==========================================
    // 7. 総合タスク管理 (Tasks)
    // ==========================================
    Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/gantt', 'gantt')->name('gantt'); // ガントチャート
        Route::post('/import', 'importBacklog')->name('import'); // Backlog取り込み
        Route::post('/{id}/status', 'updateStatus')->name('updateStatus');
    });

    // ==========================================
    // 8. 設定 (Settings)
    // ==========================================
    Route::controller(SettingController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::post('/', 'update')->name('update');
    });

    // ==========================================
    // 9. デバッグ・その他
    // ==========================================
    Route::get('/ai-chat', function () {
        return view('ai-chat');
    })->name('ai-chat');

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

});