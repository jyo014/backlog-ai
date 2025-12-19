<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_message',
        'ai_response',
    ];
}