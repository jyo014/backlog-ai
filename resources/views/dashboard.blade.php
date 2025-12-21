<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex h-[calc(100vh-65px)] overflow-hidden bg-gray-50">

        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col shadow-sm z-10">
            <div class="p-6">
                <h1 class="text-2xl font-extrabold text-indigo-600 tracking-tighter">BacklogAI</h1>
                <p class="text-xs text-gray-400 mt-1">Student Management</p>
            </div>
            
            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu</p>
                
                {{-- ホーム --}}
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-indigo-600 bg-indigo-50 rounded-xl transition-all font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    ホーム
                </a>

                {{-- 情報共有 --}}
                <a href="{{ route('team.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    情報共有
                </a>

                {{-- ▼▼▼ 追加：日程管理 ▼▼▼ --}}
                <a href="{{ route('schedule.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    日程管理
                </a>
                {{-- ▼▼▼ 追加：タスク管理 ▼▼▼ --}}
                <a href="{{ route('tasks.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs('tasks.index') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="font-medium">タスク管理</span>
                </a>

                <div class="border-t border-gray-100 my-2"></div>
                
                {{-- AIチャット --}}
                <a href="{{ route('gemini.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    AIチャット
                </a>

                {{-- 設定 --}}
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    設定
                </a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-6xl mx-auto space-y-6">
                
                {{-- AIマネージャー --}}
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-1">
                    <div class="bg-white rounded-xl p-4 flex items-center gap-4">
                        <div class="bg-indigo-100 p-3 rounded-full shrink-0">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm">AI マネージャー</h4>
                            <p class="text-gray-600 text-sm mt-1">
                                「{{ Auth::user()->name }}さん、今週の進捗は <span class="font-bold text-indigo-600">{{ $progressPercent ?? 0 }}%</span> です。
                                @if(isset($progressPercent) && $progressPercent > 80)
                                    すごい！この調子です！🚀
                                @else
                                    まずは一つ目のタスクから始めましょう！💪
                                @endif
                                」
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <a href="{{ route('gemini.index') }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition font-medium">相談する</a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- 左側カラム：カレンダー・学習分析 --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="font-bold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    今週のスケジュール
                                </h3>
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">Weekly View</span>
                            </div>

                            <div class="grid grid-cols-5 gap-3 h-64">
                                @php
                                    $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                                    $startOfWeek = now()->startOfWeek();
                                @endphp
                                @foreach($weekDays as $index => $day)
                                    @php
                                        $targetDate = $startOfWeek->copy()->addDays($index);
                                        $isToday = $targetDate->isToday();
                                        $dateStr = $targetDate->format('Y-m-d');
                                        
                                        $dayTasks = isset($weeklyCalendarTasks) ? $weeklyCalendarTasks->filter(function($task) use ($dateStr) {
                                            return \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') === $dateStr;
                                        }) : collect([]);
                                    @endphp
                                    <div class="flex flex-col h-full">
                                        <div class="text-xs font-bold text-gray-400 uppercase mb-2 text-center {{ $isToday ? 'text-indigo-600' : '' }}">{{ $day }}</div>
                                        <div class="flex-1 border {{ $isToday ? 'border-indigo-200 bg-indigo-50' : 'border-gray-100 bg-gray-50' }} rounded-xl p-2 hover:shadow-md transition text-left overflow-y-auto relative group flex flex-col gap-2">
                                            <div class="text-center mb-1">
                                                <span class="text-sm font-bold {{ $isToday ? 'text-indigo-600 bg-white px-2 py-0.5 rounded-full shadow-sm' : 'text-gray-700' }}">
                                                    {{ $targetDate->format('d') }}
                                                </span>
                                            </div>
                                            
                                            @foreach($dayTasks as $task)
                                                <div class="text-[10px] bg-white border border-gray-200 p-1.5 rounded shadow-sm text-gray-700 truncate cursor-pointer hover:border-indigo-300">
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full mr-1 {{ $task->priority == 'high' ? 'bg-red-500' : ($task->priority == 'medium' ? 'bg-yellow-400' : 'bg-blue-400') }}"></span>
                                                    {{ $task->title }}
                                                </div>
                                            @endforeach

                                            <button class="mt-auto w-full text-center py-1 opacity-0 group-hover:opacity-100 text-gray-400 hover:text-indigo-500 hover:bg-white rounded text-xs transition">
                                                + 追加
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="bg-pink-100 text-pink-500 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </span>
                                <h3 class="font-bold text-gray-700">学習時間分析</h3>
                            </div>
                            <div class="flex items-end justify-between h-32 gap-2 px-2">
                                <div class="w-full bg-gray-100 hover:bg-pink-300 transition-all rounded-t-md h-[40%] relative group"><span class="opacity-0 group-hover:opacity-100 absolute -top-6 left-1/2 -translate-x-1/2 text-xs bg-gray-800 text-white px-2 py-1 rounded">2h</span></div>
                                <div class="w-full bg-gray-100 hover:bg-pink-300 transition-all rounded-t-md h-[60%] relative group"><span class="opacity-0 group-hover:opacity-100 absolute -top-6 left-1/2 -translate-x-1/2 text-xs bg-gray-800 text-white px-2 py-1 rounded">3h</span></div>
                                <div class="w-full bg-gray-100 hover:bg-pink-300 transition-all rounded-t-md h-[30%] relative group"><span class="opacity-0 group-hover:opacity-100 absolute -top-6 left-1/2 -translate-x-1/2 text-xs bg-gray-800 text-white px-2 py-1 rounded">1.5h</span></div>
                                <div class="w-full bg-pink-500 shadow-lg shadow-pink-200 rounded-t-md h-[80%] relative group"><span class="opacity-0 group-hover:opacity-100 absolute -top-6 left-1/2 -translate-x-1/2 text-xs bg-gray-800 text-white px-2 py-1 rounded">4h</span></div>
                                <div class="w-full bg-gray-100 hover:bg-pink-300 transition-all rounded-t-md h-[50%] relative group"><span class="opacity-0 group-hover:opacity-100 absolute -top-6 left-1/2 -translate-x-1/2 text-xs bg-gray-800 text-white px-2 py-1 rounded">2.5h</span></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400 mt-2 px-1">
                                <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span>
                            </div>
                        </div>
                    </div>

                    {{-- 右側カラム --}}
                    <div class="space-y-6">
                        
                        {{-- 今日のスケジュール（授業と課題） --}}
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                            
                            <h3 class="font-bold text-lg mb-4 flex items-center relative z-10">
                                <span class="mr-2">📅</span> 今日の予定 ({{ $todayStr ?? '-' }})
                            </h3>

                            <div class="space-y-3 relative z-10">
                                {{-- 1. 今日の授業 --}}
                                @if(isset($todayCourses) && count($todayCourses) > 0)
                                    @foreach($todayCourses as $course)
                                        <div class="bg-white/10 border border-white/20 p-3 rounded-lg flex items-center backdrop-blur-sm">
                                            <div class="font-bold text-xl mr-3 w-8 text-center bg-white text-indigo-700 rounded shadow-sm">
                                                {{ $course->period }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm">{{ $course->course_name }}</p>
                                                <p class="text-xs text-indigo-100">{{ $course->university_name }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-indigo-200 bg-white/5 p-3 rounded-lg text-center">
                                        今日の授業はありません ☕️
                                    </p>
                                @endif

                                {{-- 2. 今日のタスク --}}
                                @if(isset($todayTasks) && count($todayTasks) > 0)
                                    <div class="mt-4 pt-4 border-t border-white/20">
                                        <p class="text-xs font-bold text-pink-200 mb-2">⚡️ 今日が期限の課題</p>
                                        @foreach($todayTasks as $task)
                                            <div class="flex items-center text-sm bg-pink-500/20 p-2 rounded border border-pink-400/30">
                                                <span class="mr-2">⚠️</span> {{ $task->title }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                    <span class="text-red-500">🔥</span>
                                    最優先タスク
                                </h3>
                                <span class="text-xs text-gray-400">上位5件</span>
                            </div>
                            <div class="p-4 max-h-[400px] overflow-y-auto space-y-3">
                                @if(isset($missions) && count($missions) > 0)
                                    @foreach($missions as $mission)
                                        <div class="flex items-start p-3 bg-white border border-red-100 rounded-xl shadow-sm hover:shadow-md transition group">
                                            <div class="mt-1 w-2 h-2 rounded-full bg-red-500 shrink-0 mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-700 leading-snug group-hover:text-red-600 transition">{{ $mission->title }}</p>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-xs text-gray-400 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        {{ \Carbon\Carbon::parse($mission->due_date)->format('m/d H:i') }}
                                                    </span>
                                                    <button class="text-xs bg-gray-50 text-gray-500 hover:bg-green-500 hover:text-white px-2 py-1 rounded border border-gray-200 transition">
                                                        完了
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                        <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm">緊急タスクはありません</p>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                                <a href="#" class="text-xs text-indigo-600 font-bold hover:underline">全てのタスクを見る →</a>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-yellow-100 to-orange-100 rounded-2xl p-6 shadow-sm">
                            <h4 class="font-bold text-yellow-800 mb-2 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tips
                            </h4>
                            <p class="text-xs text-yellow-900 leading-relaxed">
                                「大学授業」メニューから、みんなが共有したテスト範囲や課題情報を確認できます。情報を追加してチームに貢献しましょう！
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>