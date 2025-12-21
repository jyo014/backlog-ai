<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Task Manager</title>
    <style>
        /* 基本設定 */
        body { 
            margin: 0; 
            padding: 0; 
            height: 100vh; 
            font-family: 'Helvetica Neue', Arial, sans-serif; 
            background-color: #f5f7fb; 
            color: #333; 
            display: flex; 
            flex-direction: column; 
        }

        /* ヘッダー */
        .app-header { 
            background: #fff; 
            padding: 15px 20px; 
            text-align: center; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            z-index: 10; 
            font-weight: bold; 
            font-size: 1.2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }

        /* メインコンテナ（画面いっぱい） */
        .main-container { 
            flex: 1; 
            display: flex; 
            overflow: hidden; 
            /* 以前はmax-widthなどで制限していましたが、全幅にします */
            width: 100%;
        }

        /* チャットセクション（全幅） */
        .chat-section { 
            flex: 1; /* flex: 2 から変更して全幅占有 */
            display: flex; 
            flex-direction: column; 
            /* border-right を削除 */
            background: #fff; 
            max-width: 1000px; /* あまり広すぎると読みづらいので、中央寄せ用の制限を入れる */
            margin: 0 auto; /* 中央寄せ */
            width: 100%;
            border-left: 1px solid #eee; /* 左右に境界線を追加して見た目を整える */
            border-right: 1px solid #eee;
        }

        /* チャット履歴エリア */
        .chat-history { 
            flex: 1; 
            overflow-y: auto; 
            padding: 30px; /* パディングを少し広げました */
            display: flex; 
            flex-direction: column; 
            gap: 20px; 
        }

        /* 入力エリア */
        .input-area { 
            padding: 20px 30px; 
            border-top: 1px solid #eee; 
            background: #f9f9f9; 
        }
        .input-form { display: flex; gap: 10px; }
        
        textarea { 
            flex: 1; 
            padding: 15px; 
            border-radius: 8px; 
            border: 1px solid #ccc; 
            resize: none; 
            height: 50px; 
            font-family: inherit;
        }

        .btn-send { 
            background: #4f46e5; 
            color: white; 
            border: none; 
            padding: 0 25px; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-send:hover { background: #4338ca; }

        /* メッセージスタイル */
        .message { 
            max-width: 85%; /* 少し広げました */
            padding: 15px 20px; 
            border-radius: 12px; 
            line-height: 1.6; 
            font-size: 15px; 
        }
        .user-message { 
            align-self: flex-end; 
            background: #d1e7dd; 
            color: #0f5132; 
        }
        .ai-message { 
            align-self: flex-start; 
            background: #f0f0f0; 
            color: #333; 
        }
        pre { white-space: pre-wrap; margin: 0; font-family: inherit; }

    </style>
</head>
<body>

    <div class="app-header">
        <a href="{{ route('dashboard') }}" style="text-decoration: none; color: #4f46e5; font-size: 14px; display: flex; align-items: center; gap: 5px;">
            ⬅ ダッシュボードへ
        </a>

        <span>AI Task Manager</span>
        
        {{-- ヘッダーの右側が寂しいので、空のdivでバランスを取るか、別の機能を入れる余地 --}}
        <div style="width: 100px;"></div> 
    </div>

    <div class="main-container">
        <div class="chat-section">
            <div class="chat-history" id="chat-box">
                @foreach($chats as $chat)
                    <div class="message user-message">{!! nl2br(e($chat->user_message)) !!}</div>
                    <div class="message ai-message"><pre>{{ $chat->ai_response }}</pre></div>
                @endforeach
            </div>
            <div class="input-area">
                <form method="POST" action="{{ route('gemini.post') }}" class="input-form">
                    @csrf
                    <textarea name="sentence" placeholder="進捗どう？と聞いてみてください..." required></textarea>
                    <button type="submit" class="btn-send">送信</button>
                </form>
            </div>
        </div>

        {{-- ★★★ 右側の .task-section を削除しました ★★★ --}}
    </div>
    
    <script>
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</body>
</html>