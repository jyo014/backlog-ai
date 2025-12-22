<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('週間スケジュール管理') }}
            </h2>
            <div class="flex space-x-2">
                 {{-- 簡易操作ボタン --}}
                <div class="flex bg-white rounded-md shadow-sm">
                    <button onclick="shiftWeek(-1)" class="px-3 py-1 text-sm border border-r-0 rounded-l hover:bg-gray-50 text-gray-600">＜ 先週</button>
                    <button onclick="resetToToday()" class="px-3 py-1 text-sm border font-bold text-indigo-600 hover:bg-gray-50">今日</button>
                    <button onclick="shiftWeek(1)" class="px-3 py-1 text-sm border border-l-0 rounded-r hover:bg-gray-50 text-gray-600">翌週 ＞</button>
                </div>
                <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline flex items-center ml-4">
                    タスク一覧に戻る
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        /* --- レイアウト全体 --- */
        .schedule-container {
            display: flex;
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 6px;
            margin-top: 20px;
            height: 600px;
            overflow: hidden;
        }

        /* --- 左パネル：タスクリスト --- */
        .left-panel {
            width: 400px;
            flex-shrink: 0;
            border-right: 1px solid #d1d5db;
            background: #fff;
            display: flex;
            flex-direction: column;
            z-index: 20;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .list-header {
            height: 80px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: end;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            box-sizing: border-box;
        }

        .list-body {
            flex: 1;
            overflow: hidden;
            background: #fff;
        }

        .task-row {
            height: 50px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            padding: 0 12px;
            font-size: 13px;
            color: #374151;
            box-sizing: border-box;
            transition: background 0.1s;
        }
        .task-row:hover { background: #f9fafb; }

        .col-subject { flex: 1; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-right: 10px; }
        .col-user { width: 40px; display: flex; justify-content: center; }
        .col-status { width: 70px; text-align: right; }

        .avatar { width: 24px; height: 24px; border-radius: 50%; color: #fff; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .badge { font-size: 10px; padding: 2px 8px; border-radius: 10px; color: #fff; font-weight: bold; }
        .bg-red { background: #ef4444; } .bg-blue { background: #3b82f6; } .bg-green { background: #10b981; }


        /* --- 右パネル：週間スケジュールグリッド --- */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: auto;
            background: #fff;
            position: relative;
        }

        /* 日付ヘッダー */
        .calendar-header {
            display: flex;
            height: 80px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .header-cell {
            flex: 1;
            min-width: 100px;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: #6b7280;
            position: relative;
        }
        .header-day { font-size: 11px; margin-bottom: 4px; }
        .header-date { font-size: 18px; font-weight: bold; color: #1f2937; }
        
        .header-cell.today { background-color: #eff6ff; color: #2563eb; }
        .header-cell.today .header-date { color: #2563eb; }
        .header-cell.today::after {
            content: ""; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: #2563eb;
        }

        /* カレンダー本体 */
        .calendar-body {
            flex: 1;
            overflow-y: auto;
            position: relative;
        }
        
        .grid-row {
            display: flex;
            height: 50px;
            border-bottom: 1px solid #f3f4f6;
            position: relative;
        }
        .grid-row:hover { background: #fafafa; }

        .grid-cell {
            flex: 1;
            min-width: 100px;
            border-right: 1px dashed #e5e7eb;
            position: relative;
        }
        .grid-cell.weekend { background-color: #f9fafb; }
        .grid-cell.today-col { background-color: #f0f9ff; opacity: 0.5; }

        /* スケジュールバー */
        .task-bar {
            position: absolute;
            top: 10px; bottom: 10px;
            border-radius: 4px;
            padding: 0 8px;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.1s;
            z-index: 5;
        }
        .task-bar:hover { transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.15); z-index: 10; }

        .bar-todo { background: #fca5a5; color: #7f1d1d; border-left: 4px solid #ef4444; }
        .bar-doing { background: #bfdbfe; color: #1e3a8a; border-left: 4px solid #3b82f6; }
        .bar-done { background: #bbf7d0; color: #14532d; border-left: 4px solid #22c55e; }

    </style>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="schedule-container">
                {{-- 左：タスクリスト --}}
                <div class="left-panel">
                    <div class="list-header">
                        <div class="col-subject">件名</div>
                        <div class="col-user">担当</div>
                        <div class="col-status">状態</div>
                    </div>
                    <div class="list-body" id="left-body-container"></div>
                </div>

                {{-- 右：週間スケジュール --}}
                <div class="right-panel">
                    <div class="calendar-header" id="calendar-header"></div>
                    <div class="calendar-body" id="calendar-body-container"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const today = new Date();
        let currentStartDate = new Date(today);
        
        // 週の始まり（月曜日）にセット
        const day = currentStartDate.getDay();
        const diff = currentStartDate.getDate() - day + (day == 0 ? -6 : 1); 
        currentStartDate.setDate(diff);

        // 日付加算ヘルパー (Dateオブジェクトを返す)
        function addDays(date, days) {
            const d = new Date(date);
            d.setDate(d.getDate() + days);
            return d;
        }

        // 表示用フォーマット
        function formatDate(date) {
            return (date.getMonth() + 1) + '/' + date.getDate();
        }

        // タスクデータをカンバンボードと一致させる ★★★
        const tasks = [
            { 
                id: 1, 
                name: 'NEC25_NULAB_OKABE-1 課題1', 
                user: '岡部', 
                status: '未対応', 
                start: addDays(today, -5), // 5日前開始
                end: addDays(today, -1),   // 1日前期限 (期限切れ)
                type: 'todo' 
            },
            { 
                id: 2, 
                name: 'NEC25_NULAB_OKABE-2 課題2', 
                user: '岡部', 
                status: '処理中', 
                start: addDays(today, -2), // 2日前開始
                end: addDays(today, 3),    // 3日後期限
                type: 'doing' 
            },
            { 
                id: 3, 
                name: 'NEC25_NULAB_OKABE-3 課題3', 
                user: '岡部', 
                status: '完了', 
                start: addDays(today, -4), 
                end: addDays(today, 0),    // 今日完了
                type: 'done' 
            },
            { 
                id: 4, 
                name: '画面デザイン作成', 
                user: '田中', 
                status: '未対応', 
                start: addDays(today, 1), 
                end: addDays(today, 8),    // 12/29頃
                type: 'todo' 
            },
            { 
                id: 5, 
                name: 'API実装', 
                user: '佐藤', 
                status: '未対応', 
                start: addDays(today, 4), 
                end: addDays(today, 12),   // 1/02頃
                type: 'todo' 
            },
            { 
                id: 6, 
                name: '結合テスト', 
                user: '鈴木', 
                status: '未対応', 
                start: addDays(today, 10), 
                end: addDays(today, 18),   // 1/08頃
                type: 'todo' 
            }
        ];

        document.addEventListener('DOMContentLoaded', function() {
            renderView();
            setupScrollSync();
        });

        function renderView() {
            renderHeader();
            renderLeftPanel();
            renderRightPanel();
        }

        // 1. ヘッダー (月〜日)
        function renderHeader() {
            const header = document.getElementById('calendar-header');
            const days = ['日', '月', '火', '水', '木', '金', '土'];
            let html = '';

            for (let i = 0; i < 7; i++) {
                const d = addDays(currentStartDate, i);
                const isToday = d.toDateString() === today.toDateString();
                const dayName = days[d.getDay()];
                
                html += `
                    <div class="header-cell ${isToday ? 'today' : ''}">
                        <div class="header-day">${d.getMonth()+1}/${d.getDate()} (${dayName})</div>
                        ${isToday ? '<div class="text-xs mt-1">Today</div>' : ''}
                    </div>
                `;
            }
            header.innerHTML = html;
        }

        // 2. 左パネル (タスクリスト)
        function renderLeftPanel() {
            const container = document.getElementById('left-body-container');
            let html = '';

            tasks.forEach(task => {
                let badgeClass = 'bg-red';
                let avatarColor = '#ef4444';
                if(task.type === 'doing') { badgeClass = 'bg-blue'; avatarColor = '#3b82f6'; }
                if(task.type === 'done') { badgeClass = 'bg-green'; avatarColor = '#10b981'; }

                html += `
                    <div class="task-row">
                        <div class="col-subject" title="${task.name}">${task.name}</div>
                        <div class="col-user">
                            <div class="avatar" style="background:${avatarColor}">${task.user.charAt(0)}</div>
                        </div>
                        <div class="col-status">
                            <span class="badge ${badgeClass}">${task.status}</span>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // 3. 右パネル (スケジュールバー)
        function renderRightPanel() {
            const container = document.getElementById('calendar-body-container');
            let html = '';

            // 表示範囲 (開始日 00:00:00 〜 7日後 00:00:00)
            const viewStart = new Date(currentStartDate);
            viewStart.setHours(0,0,0,0);
            const viewEnd = addDays(viewStart, 7);

            tasks.forEach(task => {
                html += `<div class="grid-row">`;
                
                // 7日分の背景セル
                for(let i=0; i<7; i++) {
                    const d = addDays(currentStartDate, i);
                    const isToday = d.toDateString() === today.toDateString();
                    const isWeekend = (d.getDay() === 0 || d.getDay() === 6);
                    html += `<div class="grid-cell ${isWeekend ? 'weekend' : ''} ${isToday ? 'today-col' : ''}"></div>`;
                }

                // --- バーの描画ロジック ---
                // タスク期間が表示範囲と重なる場合のみ描画
                if (task.end > viewStart && task.start < viewEnd) {
                    // 範囲内に収まるように開始・終了をクリップ
                    const visualStart = task.start < viewStart ? viewStart : task.start;
                    const visualEnd = task.end > viewEnd ? viewEnd : task.end;

                    // 差分(ミリ秒)を日数に変換
                    const offsetMs = visualStart - viewStart;
                    const durationMs = visualEnd - visualStart;
                    
                    const offsetDays = offsetMs / (1000 * 60 * 60 * 24);
                    const durationDays = durationMs / (1000 * 60 * 60 * 24);

                    // 幅計算 (%)
                    const leftPct = (offsetDays / 7) * 100;
                    const widthPct = (durationDays / 7) * 100; // +0.1は微調整

                    let barClass = 'bar-todo';
                    if(task.type === 'doing') barClass = 'bar-doing';
                    if(task.type === 'done') barClass = 'bar-done';

                    html += `
                        <div class="task-bar ${barClass}" 
                             style="left: ${leftPct}%; width: ${widthPct}%;"
                             title="${task.name} (${formatDate(task.start)} ~ ${formatDate(task.end)})">
                            ${task.name}
                        </div>
                    `;
                }

                html += `</div>`;
            });

            container.innerHTML = html;
        }

        // --- 操作系 ---
        function shiftWeek(offset) {
            currentStartDate.setDate(currentStartDate.getDate() + (offset * 7));
            renderView();
        }

        function resetToToday() {
            const now = new Date();
            const day = now.getDay();
            const diff = now.getDate() - day + (day == 0 ? -6 : 1); 
            currentStartDate = new Date(now);
            currentStartDate.setDate(diff);
            renderView();
        }

        // スクロール同期
        function setupScrollSync() {
            const left = document.getElementById('left-body-container');
            const right = document.getElementById('calendar-body-container');
            
            let isSyncingLeft = false;
            let isSyncingRight = false;

            left.addEventListener('scroll', function() {
                if(!isSyncingLeft) { isSyncingRight = true; right.scrollTop = this.scrollTop; }
                isSyncingLeft = false;
            });

            right.addEventListener('scroll', function() {
                if(!isSyncingRight) { isSyncingLeft = true; left.scrollTop = this.scrollTop; }
                isSyncingRight = false;
            });
            
            left.addEventListener('wheel', (e) => {
                e.preventDefault();
                right.scrollTop += e.deltaY;
            });
        }
    </script>
</x-app-layout>