<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            
            {{-- 左側：戻るボタン ＋ タイトル --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100" title="ダッシュボードに戻る">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📅 日程管理
                </h2>
            </div>

            {{-- 中央：操作エリア（切り替え & 移動） --}}
            <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 p-1">
                {{-- 前へボタン --}}
                <a href="{{ route('schedule.index', ['mode' => $mode, 'date' => $baseDate->copy()->sub($mode === 'month' ? '1 month' : '1 week')->format('Y-m-d')]) }}" 
                   class="p-1.5 rounded hover:bg-gray-100 text-gray-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>

                {{-- 年月表示 --}}
                <span class="px-4 font-bold text-gray-700 w-32 text-center whitespace-nowrap">
                    {{ $baseDate->format('Y年n月') }}
                </span>

                {{-- 次へボタン --}}
                <a href="{{ route('schedule.index', ['mode' => $mode, 'date' => $baseDate->copy()->add($mode === 'month' ? '1 month' : '1 week')->format('Y-m-d')]) }}" 
                   class="p-1.5 rounded hover:bg-gray-100 text-gray-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            {{-- 右側：モード切り替えタブ --}}
            <div class="flex bg-gray-100 rounded-lg p-1">
                <a href="{{ route('schedule.index', ['mode' => 'week', 'date' => $baseDate->format('Y-m-d')]) }}" 
                   class="px-4 py-1.5 text-sm font-bold rounded-md transition {{ $mode === 'week' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    週間
                </a>
                <a href="{{ route('schedule.index', ['mode' => 'month', 'date' => $baseDate->format('Y-m-d')]) }}" 
                   class="px-4 py-1.5 text-sm font-bold rounded-md transition {{ $mode === 'month' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    月間
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 h-[calc(100vh-80px)] bg-white flex flex-col overflow-hidden">
        
        {{--  1. ヘッダーエリア（時間軸）--}}
        <div class="flex border-b border-gray-200 bg-gray-50">
            <div class="w-24 shrink-0 border-r border-gray-200 bg-gray-100 z-20"></div>
            <div class="flex-1 overflow-hidden" id="headerScrollSync">
                <div class="flex min-w-[1440px]"> 
                    @for($h = 0; $h < 24; $h++)
                        <div class="flex-1 border-r border-gray-200 text-xs text-gray-500 py-2 pl-1 relative">
                            {{ sprintf('%d:00', $h) }}
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- 2. メインエリア（日付行 × 時間軸）--}}
        <div class="flex-1 overflow-y-auto overflow-x-auto relative custom-scrollbar" id="mainScrollContainer">
            <div class="min-w-[1440px]"> 
                
                {{-- $periodDays をループ --}}
                @foreach($periodDays as $day)
                    <div class="flex border-b border-gray-100 group h-20 relative hover:bg-gray-50 transition">
                        
                        {{-- 日付ラベル --}}
                        <div class="w-24 shrink-0 border-r border-gray-200 bg-white sticky left-0 z-10 flex flex-col items-center justify-center {{ $day->isToday() ? 'bg-blue-50' : '' }}">
                            <span class="text-xs font-bold {{ $day->isToday() ? 'text-blue-600' : 'text-gray-500' }} uppercase">{{ $day->isoFormat('ddd') }}</span>
                            <span class="text-xl font-bold {{ $day->isToday() ? 'text-blue-600' : 'text-gray-800' }}">{{ $day->format('j') }}</span>
                        </div>

                        {{-- タイムラインエリア --}}
                        <div class="flex-1 relative">
                            {{-- 背景グリッド --}}
                            <div class="absolute inset-0 flex pointer-events-none">
                                @for($h = 0; $h < 24; $h++)
                                    <div class="flex-1 border-r border-gray-100 border-dashed"></div>
                                @endfor
                            </div>

                            {{-- クリック用エリア --}}
                            <div class="absolute inset-0 flex z-0">
                                @for($h = 0; $h < 24; $h++)
                                    <div class="flex-1 cursor-pointer hover:bg-black/5 transition"
                                         onclick="openCreateModal('{{ $day->format('Y-m-d') }}', '{{ sprintf('%02d:00', $h) }}')">
                                    </div>
                                @endfor
                            </div>

                            {{-- 予定バーの描画 --}}
                            @foreach($schedules as $schedule)
                                @php
                                    $start = \Carbon\Carbon::parse($schedule->start_date);
                                    $end = \Carbon\Carbon::parse($schedule->end_date);
                                    $dayStart = $day->copy()->startOfDay();
                                    $dayEnd = $day->copy()->endOfDay();
                                @endphp

                                @if($start < $dayEnd && $end > $dayStart)
                                    @php
                                        $startTime = $start < $dayStart ? 0 : ($start->hour * 60 + $start->minute);
                                        $endTime = $end > $dayEnd ? 1440 : ($end->hour * 60 + $end->minute);
                                        
                                        $leftPercent = ($startTime / 1440) * 100;
                                        $widthPercent = (($endTime - $startTime) / 1440) * 100;

                                        $roundedClass = 'rounded';
                                        if ($start < $dayStart) $roundedClass = 'rounded-r rounded-l-none border-l-0';
                                        if ($end > $dayEnd) $roundedClass = 'rounded-l rounded-r-none border-r-0';
                                        if ($start < $dayStart && $end > $dayEnd) $roundedClass = 'rounded-none border-x-0';
                                    @endphp

                                    <div class="absolute top-2 bottom-2 {{ $roundedClass }} px-2 flex items-center text-xs text-white shadow-sm pointer-events-auto cursor-pointer hover:opacity-90 transition z-10 border border-white/20 overflow-hidden whitespace-nowrap"
                                         style="left: {{ $leftPercent }}%; width: {{ $widthPercent }}%; background-color: {{ $schedule->color }}; min-width: 4px;"
                                         onclick="event.stopPropagation(); openEditModal({{ $schedule }});">
                                        
                                        @if($start >= $dayStart)
                                            <span class="font-bold mr-1">{{ $schedule->title }}</span>
                                            <span class="text-[10px] opacity-80">({{ $start->format('H:i') }}-{{ $end->format('H:i') }})</span>
                                        @else
                                            <span class="text-[10px] opacity-70">◀ 続き</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- 現在時刻線 --}}
            <div id="currentTimeLine" class="absolute top-0 bottom-0 border-l-2 border-red-500 z-30 pointer-events-none hidden" style="left: 0;">
                <div class="absolute -left-[5px] -top-1 w-2.5 h-2.5 bg-red-500 rounded-full"></div>
            </div>
        </div>
    </div>

    {{--  モーダル類 --}}
    <div id="createModal" class="fixed inset-0 z-50 hidden" style="z-index: 100;">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('createModal')"></div>
        <div class="flex items-center justify-center min-h-screen p-4 pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-gray-800">予定を追加</h3>
                    <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form action="{{ route('schedule.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <input type="text" name="title" required class="w-full text-lg font-bold border-0 border-b-2 border-gray-200 focus:ring-0 px-0" placeholder="タイトル">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="datetime-local" name="start_date" id="createStart" required class="w-full bg-gray-50 border-gray-200 rounded-lg text-sm">
                        <input type="datetime-local" name="end_date" id="createEnd" required class="w-full bg-gray-50 border-gray-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">カラー</label>
                        <div class="flex gap-3">
                            @foreach(['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6', '#6366f1'] as $c)
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="color" value="{{ $c }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-full transition-transform peer-checked:scale-110 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-gray-400" style="background-color: {{ $c }}"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">キャンセル</button>
                        <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden" style="z-index: 100;">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('editModal')"></div>
        <div class="flex items-center justify-center min-h-screen p-4 pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all pointer-events-auto">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-gray-800">予定の詳細</h3>
                    <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">タイトル</p>
                        <p id="editTitle" class="text-xl font-bold text-gray-800"></p>
                    </div>
                    <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-lg">
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">開始</p>
                            <p id="editStart" class="text-sm font-medium text-gray-700 font-mono"></p>
                        </div>
                        <div class="text-gray-300">➜</div>
                        <div class="flex-1 text-right">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">終了</p>
                            <p id="editEnd" class="text-sm font-medium text-gray-700 font-mono"></p>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-between items-center border-t border-gray-100 mt-4">
                        <form id="deleteForm" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center px-2 py-1 hover:bg-red-50 rounded transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                削除する
                            </button>
                        </form>
                        <button onclick="closeModal('editModal')" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-lg">閉じる</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const headerScroll = document.getElementById('headerScrollSync');
        const mainScroll = document.getElementById('mainScrollContainer');
        mainScroll.addEventListener('scroll', () => { headerScroll.scrollLeft = mainScroll.scrollLeft; });

        function openCreateModal(date, time) {
            const modal = document.getElementById('createModal');
            const inputStart = document.getElementById('createStart');
            const inputEnd = document.getElementById('createEnd');
            const startStr = `${date}T${time}`;
            inputStart.value = startStr;
            const d = new Date(startStr);
            d.setHours(d.getHours() + 1);
            const pad = (n) => String(n).padStart(2, '0');
            const endStr = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            inputEnd.value = endStr;
            modal.classList.remove('hidden');
        }

        function openEditModal(schedule) {
            const modal = document.getElementById('editModal');
            document.getElementById('editTitle').textContent = schedule.title;
            const format = (dateStr) => {
                const d = new Date(dateStr);
                return `${d.getFullYear()}/${d.getMonth()+1}/${d.getDate()} ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;
            };
            document.getElementById('editStart').textContent = format(schedule.start_date);
            document.getElementById('editEnd').textContent = format(schedule.end_date);
            const form = document.getElementById('deleteForm');
            form.action = `/schedule/${schedule.id}`;
            modal.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function updateTimeLine() {
            const now = new Date();
            const minutes = now.getHours() * 60 + now.getMinutes();
            const percent = (minutes / 1440) * 100;
            const line = document.getElementById('currentTimeLine');
            if (line) {
                line.style.left = percent + '%';
                line.classList.remove('hidden');
                if (!window.scrolled) {
                    const container = document.getElementById('mainScrollContainer');
                    const totalWidth = 1440;
                    const scrollPos = (totalWidth * (percent / 100)) - (container.clientWidth / 2);
                    if(container) { container.scrollLeft = Math.max(0, scrollPos); window.scrolled = true; }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            updateTimeLine();
            setInterval(updateTimeLine, 60000);
        });
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</x-app-layout>