<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use App\Models\Chat;
use App\Models\Task;
use Carbon\Carbon; // 日付計算用に追加

class GeminiController extends Controller
{
    /**
     * ダッシュボード画面の表示
     * 今日の予定、進捗率、期限切れ間近のタスクを集計して表示します
     */
    public function dashboard()
    {
        $today = now()->format('Y-m-d');
        
        // 1. 今日の計画（今日が締め切り）
        $todaysTasks = Task::whereDate('deadline', $today)
                           ->where('status', '!=', '完了')
                           ->get();

        // 2. 一週間の進捗度計算用
        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = now()->endOfWeek()->format('Y-m-d');
        
        $weeklyTotal = Task::whereBetween('deadline', [$startOfWeek, $endOfWeek])->count();
        $weeklyDone = Task::whereBetween('deadline', [$startOfWeek, $endOfWeek])
                          ->where('status', '完了')
                          ->count();
        $progressPercent = $weeklyTotal > 0 ? round(($weeklyDone / $weeklyTotal) * 100) : 0;

        // 3. もうすぐ期限（右下のリスト用：直近3件）
        $upcomingTasks = Task::whereDate('deadline', '>', $today)
                             ->where('status', '!=', '完了')
                             ->orderBy('deadline', 'asc')
                             ->take(3)
                             ->get();

        // ★追加: カレンダー表示用（今週の未完了タスクを全件取得）
        $weeklyCalendarTasks = Task::whereBetween('deadline', [$startOfWeek, $endOfWeek])
                                   ->where('status', '!=', '完了')
                                   ->orderBy('deadline', 'asc')
                                   ->get();
        // ★追加: 生産性分析（今週完了したタスクの合計時間）
        // 作業時間(duration)を合計して「時間」と「分」に変換
        $weeklyMinutes = Task::whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                             ->where('status', '完了')
                             ->sum('duration'); // durationカラムの合計
        
        $hours = floor($weeklyMinutes / 60);
        $minutes = $weeklyMinutes % 60;
        $studyTimeDisplay = "{$hours}h {$minutes}m";

        // ビューに $weeklyCalendarTasks を渡すのを忘れずに！
        return view('dashboard', compact('todaysTasks', 'progressPercent', 'weeklyTotal', 'weeklyDone', 'upcomingTasks', 'weeklyCalendarTasks', 'studyTimeDisplay'));
    }

    /**
     * チャット画面の表示
     */
    public function index()
    {
        // チャット履歴を古い順に取得
        $chats = Chat::oldest()->get();
        
        // タスクを「優先度が高い順」かつ「期限が近い順」に並べて取得
        $tasks = Task::orderByRaw("FIELD(priority, '高', '中', '低')")
                     ->orderBy('deadline', 'asc')
                     ->get();

        return view('gemini', compact('chats', 'tasks'));
    }

    /**
     * AIへのメッセージ送信処理
     */
    public function post(Request $request)
    {
        $request->validate([
            'sentence' => 'required',
        ]);

        $sentence = $request->input('sentence');

        // AIに渡すタスク情報の作成
        $tasks = Task::where('status', '!=', '完了')->get();
        $taskInfo = "【現在のユーザーの課題状況】\n";
        foreach($tasks as $task) {
            $taskInfo .= "・[優先度:{$task->priority}] {$task->title} (期限:{$task->deadline->format('m/d')}, 進捗:{$task->progress}%)\n";
        }
        
        $prompt = $taskInfo . "\nあなたは優秀なプロジェクトマネージャーです。上記タスク状況を踏まえて、ユーザーの『" . $sentence . "』という発言に対し、優先度や期限を考慮した具体的なアドバイスをしてください。";

        try {
            // 無料枠で制限にかかりにくい実験版モデルを使用
            $result = Gemini::generativeModel('gemini-2.0-flash-exp')->generateContent($prompt);
            $response_text = $result->text();

            Chat::create([
                'user_message' => $sentence,
                'ai_response' => $response_text,
            ]);

        } catch (\Throwable $e) {
            // エラーが発生しても画面を停止させず、チャットにエラーを表示
            Chat::create([
                'user_message' => $sentence,
                'ai_response' => "【エラー発生】Googleの制限などにより応答できませんでした。\n詳細: " . $e->getMessage(),
            ]);
        }

        return redirect()->action([self::class, 'index']);
    }

    /**
     * 新しいタスクの保存
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'deadline' => 'required|date',
            'priority' => 'required',
        ]);

        Task::create([
            'title' => $request->title,
            'deadline' => $request->deadline,
            'status' => '未着手',
            'priority' => $request->priority,
            'progress' => 0,
        ]);

        return redirect()->action([self::class, 'index']);
    }

    /**
     * タスクを完了にする
     */
    public function completeTask($id)
    {
        $task = Task::find($id);
        $task->status = '完了';
        $task->progress = 100;
        $task->save();

        return redirect()->action([self::class, 'index']);
    }

    /**
     * タスクを削除する
     */
    public function deleteTask($id)
    {
        Task::find($id)->delete();
        return redirect()->action([self::class, 'index']);
    }
}