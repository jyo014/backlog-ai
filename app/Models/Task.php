<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'deadline',
        'status',
        // ★追加
        'priority',
        'progress',
        'backlog_key',
        //新しく追加
        'team_id',
        'duration',
        'user_id',
        'title',
        'priority',
        'due_date',
    ];
    
    protected $casts = [
        'deadline' => 'date',
    ];
}