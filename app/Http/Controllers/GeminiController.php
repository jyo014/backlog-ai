<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use App\Models\Chat;

class GeminiController extends Controller
{
    public function index()
    {
        // 過去のチャットをすべて取得
        $chats = Chat::oldest()->get();
        return view('gemini', compact('chats'));
    }

    public function post(Request $request)
    {
        $request->validate([
            'sentence' => 'required',
        ]);

        $sentence = $request->input('sentence');

        // ★修正: 混雑エラー回避のため 'gemini-1.5-flash' に変更
        // (あなたのリストにあったモデルの中で安定しているものです)
        $result = Gemini::generativeModel('gemini-flash-latest')->generateContent($sentence);
        $response_text = $result->text();

        // データベースに保存
        Chat::create([
            'user_message' => $sentence,
            'ai_response' => $response_text,
        ]);

        // 画面を再読み込み
        return redirect()->action([self::class, 'index']);
    }
}