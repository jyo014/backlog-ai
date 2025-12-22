<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // 1. 表示モード（デフォルトは 'week'）
        $mode = $request->input('mode', 'week');
        
        // 2. 基準日（指定がなければ今日）
        $dateStr = $request->input('date');
        $baseDate = $dateStr ? Carbon::parse($dateStr) : Carbon::now();

        // 3. 期間の計算
        $days = [];
        if ($mode === 'month') {
            // 月間モード：その月の1日〜末日
            $startDate = $baseDate->copy()->startOfMonth();
            $endDate = $baseDate->copy()->endOfMonth();
        } else {
            // 週間モード：その週の月曜〜日曜
            $startDate = $baseDate->copy()->startOfWeek();
            $endDate = $baseDate->copy()->endOfWeek();
        }

        // 日付配列を作成
        $periodDays = [];
        $tempDate = $startDate->copy();
        while ($tempDate <= $endDate) {
            $periodDays[] = $tempDate->copy();
            $tempDate->addDay();
        }

        // 4. データの取得
        $schedules = Schedule::where('user_id', Auth::id())
            ->whereBetween('start_date', [
                $startDate->format('Y-m-d 00:00:00'),
                $endDate->format('Y-m-d 23:59:59')
            ])
            ->get();

        // ビューに渡すデータ
        return view('schedule.index', compact('periodDays', 'schedules', 'mode', 'baseDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'color' => $request->input('color', '#3b82f6'),
        ]);

        return redirect()->back(); // 元の画面に戻るように変更
    }

    public function destroy($id)
    {
        $schedule = Schedule::where('user_id', Auth::id())->findOrFail($id);
        $schedule->delete();
        return redirect()->back(); // 元の画面に戻るように変更
    }
}