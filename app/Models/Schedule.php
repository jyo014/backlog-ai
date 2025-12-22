<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // データの書き込みを許可するカラムを指定
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'color',
        'user_id',
    ];
}