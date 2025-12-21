<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    // 設定画面の表示
    public function edit()
    {
        $user = Auth::user();
        return view('settings.edit', compact('user'));
    }

    // 設定の保存
    public function update(Request $request)
    {
        $request->validate([
            'backlog_domain' => 'nullable|string|max:255',
            'backlog_api_key' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        
        // 入力されたドメインの整形（https://などを削除してドメインのみにする）
        $domain = $request->backlog_domain;
        if ($domain) {
            $domain = str_replace(['https://', 'http://', '/'], '', $domain);
        }

        $user->update([
            'backlog_domain' => $domain,
            'backlog_api_key' => $request->backlog_api_key,
        ]);

        return redirect()->route('settings.edit')->with('status', '設定を保存しました！');
    }
}