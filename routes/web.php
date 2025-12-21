<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CourseController;
use App\Models\Task; // ★ダッシュボード用に追加
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // ★ダッシュボード用に追加
use Gemini\Laravel\Facades\Gemini;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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
    // 1. ダッシュボード (優先度高タスク表示機能付き)
    // ==========================================
    Route::get('/dashboard', function () {
        // 優先度が高い('high')タスクを期限が近い順に5件取得
        $missions = Task::where('user_id', Auth::id())
                    ->where('priority', 'high')
                    ->orderBy('due_date', 'asc')
                    ->take(5)
                    ->get();

        // もしGeminiControllerの集計データも必要なら、ここでControllerを呼ぶ形に戻す必要がありますが、
        // 一旦「ミッション表示」を優先して直接ビューを返します。
        return view('dashboard', compact('missions'));
    })->name('dashboard');


    // ==========================================
    // 2. プロフィール設定
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ==========================================
    // 3. AIチャット & 個人タスク管理
    // ==========================================
    Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');
    Route::post('/gemini', [GeminiController::class, 'post'])->name('gemini.post');
    
    // タスク操作 (追加・完了・削除)
    Route::post('/tasks', [GeminiController::class, 'storeTask'])->name('task.store');
    Route::patch('/tasks/{task}/complete', [GeminiController::class, 'completeTask'])->name('task.complete');
    Route::delete('/tasks/{task}', [GeminiController::class, 'deleteTask'])->name('task.delete');


    // ==========================================
    // 4. 大学授業・課題管理機能 (Wiki方式)
    // ==========================================
    // 授業一覧
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    // 履修登録・解除
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/detach', [CourseController::class, 'detach'])->name('courses.detach');
    // 授業詳細・課題共有
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/tasks', [CourseController::class, 'storeTask'])->name('courses.tasks.store');


    // ==========================================
    // 5. チーム機能
    // ==========================================
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team/join', [TeamController::class, 'join'])->name('team.join');
    Route::post('/team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/team/task', [TeamController::class, 'storeTeamTask'])->name('team.task.store');


    // ==========================================
    // その他 (プレースホルダー等)
    // ==========================================
    Route::get('/schedule', function () { return view('schedule'); })->name('schedule');
    Route::get('/ai-chat', function () { return view('ai-chat'); })->name('ai-chat');
});

    Route::middleware(['auth'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    
    // 【追加】新規作成用のルート
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');

    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/detach', [CourseController::class, 'detach'])->name('courses.detach');
    
    // 詳細・タスク
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/tasks', [CourseController::class, 'storeTask'])->name('courses.tasks.store');

    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
});

/*
 * デバッグ用ルート (Geminiモデル確認用)
 */
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