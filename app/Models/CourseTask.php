<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTask extends Model
{
    // CourseTask.php クラスの中に以下を追加
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'due_date',
        'description',
    ];

    // どの授業のタスクか
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // 誰が投稿したか
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
