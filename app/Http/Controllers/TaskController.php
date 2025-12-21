<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Services\BacklogService;
use Carbon\Carbon;

class TaskController extends Controller
{
    // 一覧表示（タスクボード）
    public function index()
    {
        $user = Auth::user();
        
        // --- 1. データベースから自分のタスクを取得 ---
        $query = Task::where('user_id', $user->id);
        
        $dbTodos = (clone $query)->where('status', 'todo')->orderBy('deadline')->get();
        $dbDoings = (clone $query)->where('status', 'doing')->orderBy('deadline')->get();
        $dbDones = (clone $query)->where('status', 'done')->orderBy('updated_at', 'desc')->take(10)->get();

        // --- 2. ★修正：ダミーデータを「Taskモデル」として作成 ---
        // (object)[] ではなく、(new Task())->forceFill([]) を使います
        $dummies = collect([
            (new Task())->forceFill([
                'id' => 901, 
                'title' => 'NEC25_NULAB_OKABE-1 課題1',
                'status' => 'todo',
                'deadline' => Carbon::today()->subDays(1), // 期限切れ
                'priority' => 'high',
                'backlog_key' => 'NEC25-1',
                'updated_at' => Carbon::now(),
            ]),
            (new Task())->forceFill([
                'id' => 902,
                'title' => 'NEC25_NULAB_OKABE-2 課題2',
                'status' => 'doing',
                'deadline' => Carbon::today()->addDays(3),
                'priority' => 'medium',
                'backlog_key' => 'NEC25-2',
                'updated_at' => Carbon::now(),
            ]),
            (new Task())->forceFill([
                'id' => 903,
                'title' => 'NEC25_NULAB_OKABE-3 課題3',
                'status' => 'done',
                'deadline' => Carbon::today()->addDays(5),
                'priority' => 'low',
                'backlog_key' => 'NEC25-3',
                'updated_at' => Carbon::now(),
            ]),
            (new Task())->forceFill([
                'id' => 904,
                'title' => '画面デザイン作成',
                'status' => 'todo',
                'deadline' => Carbon::today()->addDays(8),
                'priority' => 'medium',
                'backlog_key' => null,
                'updated_at' => Carbon::now(),
            ]),
            (new Task())->forceFill([
                'id' => 905,
                'title' => 'API実装',
                'status' => 'todo',
                'deadline' => Carbon::today()->addDays(12),
                'priority' => 'high',
                'backlog_key' => null,
                'updated_at' => Carbon::now(),
            ]),
            (new Task())->forceFill([
                'id' => 906,
                'title' => '結合テスト',
                'status' => 'todo',
                'deadline' => Carbon::today()->addDays(18),
                'priority' => 'medium',
                'backlog_key' => null,
                'updated_at' => Carbon::now(),
            ]),
        ]);

        // --- 3. DBデータとダミーデータを合体させる ---
        // これでエラー（getKey undefined）が消えます
        $todos = $dbTodos->merge($dummies->where('status', 'todo'));
        $doings = $dbDoings->merge($dummies->where('status', 'doing'));
        $dones = $dbDones->merge($dummies->where('status', 'done'));

        // ダッシュボードと同じ「最近の更新」（ヘッダー用）
        $updates = [
            [
                'user' => '岡部 条',
                'action' => '課題を更新',
                'title' => 'NEC25_NULAB_OKABE-3 課題3',
                'status' => '処理済み',
                'time' => '約1分前',
                'is_highlight' => true,
            ],
            [
                'user' => '岡部 条',
                'action' => '課題を更新',
                'title' => 'NEC25_NULAB_OKABE-2 課題2',
                'status' => '処理中',
                'time' => '15分前',
                'is_highlight' => false,
            ],
        ];

        return view('tasks.index', compact('todos', 'doings', 'dones', 'updates'));
    }

    // Backlog取り込み処理
    public function importBacklog(BacklogService $backlogService)
    {
        $user = Auth::user();

        // 1. 設定チェック
        if (!$user->backlog_domain || !$user->backlog_api_key) {
            return redirect()->route('tasks.index')
                ->with('error', '設定画面でBacklogのドメインとAPIキーを保存してください。');
        }

        try {
            // 2. Serviceを使ってデータ取得
            $issues = $backlogService->getIssues($user->backlog_domain, $user->backlog_api_key);
        } catch (\Exception $e) {
            return redirect()->route('tasks.index')->with('error', $e->getMessage());
        }

        // 3. 取得した課題を保存
        $count = 0;
        foreach ($issues as $issue) {
            $exists = Task::where('user_id', $user->id)
                ->where('backlog_key', $issue['issueKey'])
                ->exists();

            if (!$exists) {
                $status = 'todo'; // デフォルト: 未対応
                if (isset($issue['status']['id'])) {
                    if ($issue['status']['id'] == 2) $status = 'doing'; // 処理中
                    if ($issue['status']['id'] >= 3) $status = 'done';  // 処理済み/完了
                }

                Task::create([
                    'user_id' => $user->id,
                    'title' => $issue['summary'],
                    'backlog_key' => $issue['issueKey'],
                    'deadline' => isset($issue['dueDate']) ? Carbon::parse($issue['dueDate']) : Carbon::now()->addWeek(),
                    'status' => $status,
                    'priority' => 'medium',
                ]);
                $count++;
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', "Backlogから {$count} 件の課題を取り込みました！");
    }

    // ステータス更新（ドラッグ＆ドロップ用）
    public function updateStatus(Request $request, $id)
    {
        // ダミーデータ(IDが900以上)の場合はDB更新をスキップ
        if ($id > 900) {
            return response()->json(['success' => true]);
        }

        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->status = $request->status;
        $task->save();
        return response()->json(['success' => true]);
    }

    // ガントチャート表示
    public function gantt()
    {
        $tasks = collect([]); 
        return view('tasks.gantt', compact('tasks'));
    }
}