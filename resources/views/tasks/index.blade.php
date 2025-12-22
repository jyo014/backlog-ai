<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('タスク管理') }}
            </h2>
            
            {{-- ▼▼▼ ボタンエリア ▼▼▼ --}}
            <div class="flex items-center space-x-4">
                
                {{-- 1. Backlog取り込みボタン --}}
                <form action="{{ route('tasks.import') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-md transition flex items-center text-sm">
                        {{-- アイコン --}}
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        Backlog取込
                    </button>
                </form>

                {{-- 2. ガントチャートボタン --}}
                <a href="{{ route('tasks.gantt') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md transition flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    ガントチャート
                </a>

                {{-- 3. 通常の追加ボタン --}}
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-md transition">
                    + 課題の追加
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        {{-- ▼ 画面幅95%で広々と表示 --}}
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ▼ 成功・エラーメッセージ表示エリア --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- 1. Backlog風：最近の動き --}}
            @if(isset($updates) && count($updates) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">📌 最近の動き</h3>
                    <span class="text-xs text-blue-500 cursor-pointer hover:underline">すべて見る</span>
                </div>
                <div class="p-0">
                    @foreach ($updates as $update)
                        <div class="flex items-center px-6 py-3 border-b border-gray-100 {{ ($update['is_highlight'] ?? false) ? 'bg-yellow-50' : 'bg-white' }} last:border-0 hover:bg-gray-50 transition">
                            <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs font-bold mr-4 shadow-sm">
                                {{ mb_substr($update['user'], 0, 1) }}
                            </div>
                            <div class="flex-1 text-sm">
                                <span class="font-bold text-gray-800">{{ $update['title'] }}</span>
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="text-xs font-bold {{ ($update['status'] ?? '') == '完了' ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $update['status'] ?? '更新' }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $update['time'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 2. カンバンボードエリア --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- ① 未対応 (ToDo) --}}
                <div class="bg-gray-100 rounded-xl p-5 border border-gray-200 flex flex-col min-h-[600px] shadow-inner">
                    <div class="flex justify-between items-center mb-6 pb-2 border-b border-gray-200">
                        <h3 class="font-bold text-lg text-gray-700 flex items-center">
                            <span class="w-3 h-3 rounded-full bg-gray-400 mr-2"></span>
                            未対応
                        </h3>
                        <span class="bg-white text-gray-700 text-sm font-bold px-3 py-1 rounded-full shadow-sm">{{ count($todos) }}</span>
                    </div>
                    <div class="space-y-4 flex-1">
                        @foreach($todos as $task)
                            <div class="bg-white p-4 rounded-lg shadow border-l-8 border-gray-400 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition duration-200">
                                <div class="text-base font-bold text-gray-800 mb-2">{{ $task->title }}</div>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span class="flex items-center">
                                        📅 {{ \Carbon\Carbon::parse($task->deadline)->format('m/d') }}
                                        @if(\Carbon\Carbon::parse($task->deadline)->isPast())
                                            <span class="ml-2 text-red-500 font-bold text-xs">期限切れ</span>
                                        @endif
                                    </span>
                                    <span class="text-xs border border-gray-200 px-2 py-0.5 rounded">{{ $task->priority ?? '中' }}</span>
                                </div>
                                @if($task->backlog_key)
                                    <div class="mt-3 text-xs bg-gray-50 text-gray-500 px-2 py-1 rounded inline-block">
                                        🔗 {{ $task->backlog_key }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ② 処理中 (Doing) --}}
                <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 flex flex-col min-h-[600px] shadow-inner">
                    <div class="flex justify-between items-center mb-6 pb-2 border-b border-blue-200">
                        <h3 class="font-bold text-lg text-blue-800 flex items-center">
                            <span class="w-3 h-3 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                            処理中
                        </h3>
                        <span class="bg-white text-blue-800 text-sm font-bold px-3 py-1 rounded-full shadow-sm">{{ count($doings) }}</span>
                    </div>
                    <div class="space-y-4 flex-1">
                        @foreach($doings as $task)
                            <div class="bg-white p-4 rounded-lg shadow-md border-l-8 border-blue-500 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition duration-200">
                                <div class="text-base font-bold text-gray-800 mb-2">{{ $task->title }}</div>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>📅 {{ \Carbon\Carbon::parse($task->deadline)->format('m/d') }}</span>
                                    <div class="flex -space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 border-2 border-white flex items-center justify-center text-xs font-bold text-blue-600">自分</div>
                                    </div>
                                </div>
                                @if($task->backlog_key)
                                    <div class="mt-3 text-xs bg-blue-50 text-blue-500 px-2 py-1 rounded inline-block">
                                        🔗 {{ $task->backlog_key }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ③ 完了 (Done) --}}
                <div class="bg-green-50 rounded-xl p-5 border border-green-100 flex flex-col min-h-[600px] shadow-inner">
                    <div class="flex justify-between items-center mb-6 pb-2 border-b border-green-200">
                        <h3 class="font-bold text-lg text-green-800 flex items-center">
                            <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                            完了
                        </h3>
                        <span class="bg-white text-green-800 text-sm font-bold px-3 py-1 rounded-full shadow-sm">{{ count($dones) }}</span>
                    </div>
                    <div class="space-y-4 flex-1 opacity-80">
                        @foreach($dones as $task)
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 group hover:bg-white transition">
                                <div class="flex items-center mb-2">
                                    <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mr-2">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-base text-gray-500 line-through decoration-gray-400 group-hover:no-underline group-hover:text-gray-800 transition">{{ $task->title }}</div>
                                </div>
                                <div class="text-xs text-gray-400 text-right">
                                    完了日: {{ $task->updated_at->format('m/d H:i') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>