<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseTask;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // 授業一覧を表示
    public function index()
    {
        // 全ての授業を取得
        $courses = Course::all();
        
        // 自分が履修している授業のIDリストを取得
        $myCourseIds = Auth::user()->courses()->pluck('courses.id')->toArray();
        
        return view('courses.index', compact('courses', 'myCourseIds'));
    }

    // 【修正】新規授業の手動登録処理
    // CourseController.php の storeメソッド

public function store(Request $request)
{
    // バリデーション
    $validated = $request->validate([
        'course_name' => 'required|string|max:255',
        'university_name' => 'required|string|max:255',
        'teacher_name' => 'required|string|max:255', // 新規追加
        'day_of_week' => 'required|string',
        'period' => 'required|integer',
    ]);
    //データの保存場所
    Course::create ($validated);

    // 授業を作成
    $course = Course::create([
        'course_name' => $validated['course_name'],
        'university_name' => $validated['university_name'] ?? null,
        'teacher_name' => $validated['teacher_name'] ?? null, // ← 追加
        'day_of_week' => $validated['day_of_week'],
        'period' => $validated['period'],
    ]);

    // 作成した授業を、作成者の履修リストに自動で追加
    Auth::user()->courses()->syncWithoutDetaching([$course->id]);
    // 一覧ページに戻ってメッセージを表示
    return redirect()->route('courses.index')
        ->with('success', '新しい授業を追加しました！');
}

    // 履修登録処理（既存の授業を登録）
    public function enroll(Course $course)
    {
        Auth::user()->courses()->syncWithoutDetaching([$course->id]);
        return back()->with('success', '履修登録しました！');
    }

    // 履修解除処理
    public function detach(Course $course)
    {
        Auth::user()->courses()->detach($course->id);
        return back()->with('success', '履修を解除しました。');
    }

    // 授業詳細画面（タスク表示・追加）
    public function show(Course $course)
    {
        $course->load(['courseTasks' => function($query) {
            $query->latest();
        }]);

        return view('courses.show', compact('course'));
    }

    // タスク追加処理
    public function storeTask(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required|date',
        ]);

        if (!Auth::user()->courses()->where('courses.id', $course->id)->exists()) {
            return back()->with('error', '履修者のみ追加できます');
        }

        CourseTask::create([
            'course_id' => $course->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        return back()->with('success', '課題を追加しました！');
    }
}