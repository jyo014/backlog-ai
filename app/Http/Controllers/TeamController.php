<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Task;
use App\Models\User;
use App\Models\University; // ★重要：これを追加しないと大学が取得できません
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 変数の初期化（エラー回避のため）
        $team = null;
        $members = [];
        $teamTasks = [];
        $myCourses = [];
        $allTeams = [];
        $universities = []; // ★追加：大学一覧用
        
        // 今日の予定用データ
        $todayCourses = [];
        $todayTasks = [];
        $todayStr = '';

        // ▼ パターンA：すでにチームに入っている場合
        if ($user->team_id) {
            $team = Team::find($user->team_id);
            
            // チームが存在する場合のみデータを取得
            if ($team) {
                $members = User::where('team_id', $team->id)->get();
                $teamTasks = Task::where('team_id', $team->id)->orderBy('deadline', 'asc')->get();
                
                // 授業情報の取得
                $myCourses = $user->courses()
                    ->orderByRaw("FIELD(day_of_week, '月', '火', '水', '木', '金', '土')")
                    ->orderBy('period')
                    ->get();

                // 今日の予定を取得するロジック
                $now = Carbon::now();
                $dayMap = ['Sun' => '日', 'Mon' => '月', 'Tue' => '火', 'Wed' => '水', 'Thu' => '木', 'Fri' => '金', 'Sat' => '土'];
                $todayStr = $dayMap[$now->format('D')];

                // 1. 今日の授業
                $todayCourses = $user->courses()
                    ->where('day_of_week', $todayStr)
                    ->orderBy('period')
                    ->get();

                // 2. 今日が期限のタスク
                $todayTasks = Task::where('team_id', $team->id)
                    ->whereDate('deadline', $now->today())
                    ->get();
            } else {
                // team_idはあるがDBにチームがない場合（稀なケース）の不整合修正
                $user->team_id = null;
                $user->save();
            }
        } 
        
        // ▼ パターンB：まだチームに入っていない場合（脱退後もここを通ります）
        if (!$user->team_id) {
            // ★重要：ここで大学一覧を取得しないと、選択画面でエラーになります
            $universities = University::all();

            // チーム検索機能
            $search = $request->input('search');
            $query = Team::query();
            
            // 大学で絞り込みたい場合はここで処理を追加できますが、一旦検索のみ
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }
            
            // チーム一覧を取得（大学情報も一緒に取得しておくと便利）
            $allTeams = $query->with('university')->orderBy('created_at', 'desc')->get();
        }

        // ビューに 'universities' を追加して渡す
        return view('team.index', compact(
            'team', 
            'members', 
            'teamTasks', 
            'myCourses', 
            'allTeams', 
            'universities', // ★ここが抜けていたのが原因です
            'todayCourses', 
            'todayTasks', 
            'todayStr'
        ));
    }

    // チーム作成処理
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'university_id' => 'required|exists:universities,id',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'university_id' => $request->university_id,
            'owner_id' => Auth::id(),
        ]);

        // 作成者をチームに所属させる
        $user = Auth::user();
        $user->team_id = $team->id;
        $user->save();

        return redirect()->route('team.index');
    }

    // チーム参加処理
    public function join(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        $user = Auth::user();
        $user->team_id = $request->team_id;
        $user->save();

        return redirect()->route('team.index');
    }

    // タスク追加処理
    public function storeTeamTask(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'deadline' => 'required|date',
        ]);

        Task::create([
            'title' => $request->title,
            'deadline' => $request->deadline,
            'team_id' => Auth::user()->team_id,
            'user_id' => Auth::id(),
            'priority' => 'medium', // デフォルト値
        ]);

        return redirect()->route('team.index');
    }

    // 脱退処理（ここも確認してください）
    public function leave(Request $request)
    {
        $user = Auth::user();
        $user->team_id = null; // チームIDを消す
        $user->save();

        return redirect()->route('team.index');
    }
}