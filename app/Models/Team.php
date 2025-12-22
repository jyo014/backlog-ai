<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'university_id', 
    ];


    public function university()
    {
        // チームは一つの大学に属する
        return $this->belongsTo(University::class);
    }

    // 既存のリレーション（もしあればそのまま残す）
    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}