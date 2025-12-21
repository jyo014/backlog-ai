<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // --- 1. 今日の日付・曜日を取得 ---
        $dayMap = ['Sun' => '日', 'Mon' => '月', 'Tue' => '火', 'Wed' => '水', 'Thu' => '木', 'Fri' => '金', 'Sat' => '土'];
        $todayStr = $dayMap[$now->format('D')];

        // --- 2. 今日の授業を取得 (Userにcoursesリレーションがある前提) ---
        // ※まだリレーションがない場合はエラーになるため、一旦空配列にするか、Userモデルにリレーションを追加してください
        $todayCourses = $user->courses 
            ? $user->courses()->where('day_of_week', $todayStr)->orderBy('period')->get() 
            : [];

        // --- 3. 今日が期限のタスクを取得 ---
        $todayTasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', $now->today())
            ->get();

        // --- 4. 最優先ミッション ---
        $missions = Task::where('user_id', $user->id)
            ->where('priority', 'high')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // --- 5. 今週のタスク ---
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();
        $weeklyCalendarTasks = Task::where('user_id', $user->id)
            ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
            ->get();

        // --- 6. AI進捗 ---
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)->where('status', '完了')->count();
        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // --- ★追加：Backlog風「最近の更新」データ ---
        // (本来はActivityLog等のテーブルから取得しますが、今はダミーで再現します)
        $updates = [
            [
                'user' => '岡部 条',
                'action' => '課題を更新',
                'title' => 'NEC25_NULAB_OKABE-3 課題3',
                'status' => '処理済み',
                'time' => '約1分前',
                'date' => '2025年12月21日(日)',
                'is_highlight' => true,
            ],
            [
                'user' => '岡部 条',
                'action' => '課題を更新',
                'title' => 'NEC25_NULAB_OKABE-3 課題3',
                'status' => '完了',
                'time' => '約1分前',
                'date' => '2025年12月21日(日)',
                'is_highlight' => true,
            ],
            [
                'user' => '岡部 条',
                'action' => '課題を更新',
                'title' => 'NEC25_NULAB_OKABE-1 課題1',
                'detail' => '[開始日: 2025-12-23] [期限日: 2026-01-04]',
                'time' => '16分前',
                'date' => '2025年12月21日(日)',
                'is_highlight' => false,
            ],
        ];

        return view('dashboard', compact(
            'todayCourses',
            'todayTasks',
            'todayStr',
            'missions',
            'weeklyCalendarTasks',
            'progressPercent',
            'updates' // ★ビューに渡す
        ));
    }
}