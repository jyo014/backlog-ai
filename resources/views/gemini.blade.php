<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini AI Chat</title>
    <style>
        /* 基本レイアウト */
        body { margin: 0; padding: 0; height: 100vh; display: flex; flex-direction: column; font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f5f7fb; color: #333; }
        .app-header { text-align: center; background-color: #fff; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); flex-shrink: 0; }
        .app-header h1 { margin: 0; font-size: 20px; color: #333; }
        
        /* チャットエリア */
        .chat-display { flex-grow: 1; overflow-y: auto; padding: 20px; width: 100%; max-width: 800px; margin: 0 auto; box-sizing: border-box; display: flex; flex-direction: column; gap: 20px; padding-bottom: 20px; }
        .message { display: flex; width: 100%; }
        .user-message { justify-content: flex-end; }
        .ai-message { justify-content: flex-start; }
        .bubble { padding: 15px 20px; border-radius: 12px; max-width: 85%; line-height: 1.6; font-size: 15px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); position: relative; }
        .user-message .bubble { background-color: #d1e7dd; color: #0f5132; border-bottom-right-radius: 2px; }
        .ai-message .bubble { background-color: #fff; border-bottom-left-radius: 2px; }
        .empty-state { text-align: center; color: #aaa; margin-top: 100px; }
        pre { white-space: pre-wrap; margin: 0; font-family: inherit; }

        /* 入力エリア */
        .input-area { background-color: #fff; padding: 20px; border-top: 1px solid #ddd; flex-shrink: 0; }
        .input-container { max-width: 800px; margin: 0 auto; display: flex; gap: 10px; }
        textarea { flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 8px; resize: none; height: 50px; font-size: 16px; font-family: inherit; }
        button { background-color: #4f46e5; color: white; border: none; padding: 0 25px; border-radius: 8px; font-weight: bold; cursor: pointer; height: 76px; transition: background-color 0.2s; }
        button:hover { background-color: #4338ca; }
        button:disabled { background-color: #ccc; cursor: not-allowed; }

        /* ローディングアニメーション */
        .loading-overlay {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 100;
            justify-content: center; align-items: center; flex-direction: column;
        }
        .spinner {
            width: 40px; height: 40px;
            border: 4px solid #f3f3f3; border-top: 4px solid #4f46e5; border-radius: 50%;
            animation: spin 1s linear infinite; margin-bottom: 10px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .loading-text { font-weight: bold; color: #4f46e5; }
    </style>
</head>
<body>

    <div class="loading-overlay" id="loading">
        <div class="spinner"></div>
        <div class="loading-text">Geminiが考え中...</div>
    </div>

    <header class="app-header">
        <h1>Gemini AI チャットフォーム</h1>
    </header>

    <main class="chat-display" id="chat-box">
        @if(isset($chats) && count($chats) > 0)
            @foreach($chats as $chat)
                <div class="message user-message">
                    <div class="bubble">
                        <strong>あなた:</strong><br>
                        {!! nl2br(e($chat->user_message)) !!}
                    </div>
                </div>
                <div class="message ai-message">
                    <div class="bubble">
                        <strong>Gemini:</strong><br>
                        <pre>{{ $chat->ai_response }}</pre>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <p>まだ会話はありません。<br>下のフォームから話しかけてみてください！</p>
            </div>
        @endif
    </main>

    <footer class="input-area">
        <form method="POST" action="{{ route('gemini.post') }}" onsubmit="showLoading()">
            @csrf
            <div class="input-container">
                <textarea 
                    name="sentence" 
                    placeholder="質問を入力... (Shift+Enterで改行)" 
                    required
                ></textarea>
                <button type="submit" id="submit-btn">送信</button>
            </div>
        </form>
    </footer>

    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'flex';
            document.getElementById('submit-btn').disabled = true;
        }

        window.onload = function() {
            var chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        };
    </script>

</body>
</html>