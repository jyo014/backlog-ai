<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    // ★ここを修正：状況に応じてデータを準備し、すべて 'team.index' に渡す
    public function index()
    {
        $user = Auth::user();
        
        // 変数の初期化（エラー防止のため空で定義しておく）
        $team = null;
        $members = [];
        $teamTasks = [];
        $myCourses = [];
        $allTeams = [];

        // ▼ パターンA：すでにチームに入っている場合
        if ($user->team_id) {
            $team = Team::find($user->team_id);
            
            // チームデータが取れた場合のみ関連データを取得
            if ($team) {
                // メンバー一覧
                $members = $team->users; // Userモデルに teams リレーションがない場合は User::where(...)
                
                // チーム課題
                $teamTasks = Task::where('team_id', $team->id)
                    ->orderBy('deadline', 'asc')
                    ->get();

                // 自分の履修授業（サイドバー用）
                $myCourses = $user->courses()
                    ->orderByRaw("FIELD(day_of_week, '月', '火', '水', '木', '金', '土')")
                    ->orderBy('period')
                    ->get();
            }
        } 
        // ▼ パターンB：まだチームに入っていない場合
        else {
            // 選択肢用の全チームリストを取得
            $allTeams = Team::all();
        }

        // どちらの場合も 'team.index' を表示する
        // View側の @if(isset($team)) が判定してくれます
        return view('team.index', compact('team', 'members', 'teamTasks', 'myCourses', 'allTeams'));
    }

    // チームに参加する
    public function join(Request $request)
    {
        $user = Auth::user();
        $user->team_id = $request->team_id;
        $user->save();
        
        return redirect()->route('team.index');
    }

    // チーム（大学）を新規作成する
    public function create(Request $request)
    {
        $request->validate(['name' => 'required']);
        
        $team = Team::create(['name' => $request->name]);
        
        // 作った人がそのまま加入
        $user = Auth::user();
        $user->team_id = $team->id;
        $user->save();

        return redirect()->route('team.index');
    }

    // チームの課題を追加する
    public function storeTeamTask(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'deadline' => 'required|date',
        ]);

        Task::create([
            'title' => $request->title,
            'deadline' => $request->deadline,
            'status' => '未着手',
            'priority' => '中',
            'team_id' => Auth::user()->team_id,
            'duration' => 60,
        ]);

        return redirect()->route('team.index');
    }
    
    // showメソッドは不要になったので削除してOKです
}