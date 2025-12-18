<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini; 

class GeminiController extends Controller
{
    public function index(Request $request) 
    {
        return view('gemini'); 
    }

    public function post(Request $request)
    {
        $request->validate([
            'sentence' => 'required',
        ]);

        $sentence = $request->input('sentence');

        // ★修正箇所: リストにあった 'gemini-2.5-flash' を指定
        $result = Gemini::generativeModel('gemini-2.5-flash')->generateContent($sentence);        
        
        $response_text = $result->text();

        return view('gemini', compact('sentence', 'response_text'));
    }
}