<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    // Course.php クラスの中に以下を追加

    // 変更してもいい項目（これがないと保存時にエラーになります）
    protected $fillable = [
        'university_name',
        'course_name',
        'teacher_name',
        'day_of_week',
        'period',
    ];

    // この授業を履修しているユーザー
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    // この授業に紐づくタスク（課題・テスト）
    public function courseTasks()
    {
        return $this->hasMany(CourseTask::class);
    }
}
