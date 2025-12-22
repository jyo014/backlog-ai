<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['name']; 
    
    // チームとのリレーション（1つの大学に複数のチームがある）
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}