<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BacklogService
{
    /**
     * BacklogのAPIを叩いて課題一覧を取得する
     */
    public function getIssues($domain, $apiKey)
    {
        // ドメインから https:// などを除去して整形
        $host = str_replace(['https://', 'http://', '/'], '', $domain);
        
        // APIエンドポイントの構築
        // ドメインが "xyz.backlog.jp" の場合 -> https://xyz.backlog.jp/api/v2/issues
        $url = "https://{$host}/api/v2/issues";

        // APIリクエスト送信
        $response = Http::get($url, [
            'apiKey' => $apiKey,
            'count' => 100,             // 最新100件取得
            'statusId' => [1, 2, 3, 4], // 未対応, 処理中, 処理済み, 完了 すべて取得
            'sort' => 'updated',        // 更新順
        ]);

        if ($response->failed()) {
            // エラー時の詳細をログに出すなどの処理が本来は必要
            throw new \Exception('Backlogとの通信に失敗しました: ' . $response->body());
        }

        return $response->json();
    }
}