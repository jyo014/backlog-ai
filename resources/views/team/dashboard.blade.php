<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-gray-700">プロジェクトホーム：最近の更新</h3>
                        <span class="text-sm text-gray-500">フィルタ：すべて | 表示設定</span>
                    </div>

                    {{-- 日付ヘッダー --}}
                    <div class="border-b border-gray-300 pb-1 mb-2 font-bold text-gray-600 text-sm">
                        2025年12月21日(日)
                    </div>

                    <div class="space-y-0">
                        @foreach ($updates as $update)
                            {{-- 更新行 --}}
                            <div class="flex items-start p-4 border-b border-gray-100 {{ $update['is_highlight'] ? 'bg-yellow-50' : 'bg-white' }}">
                                {{-- 左側：ユーザーアイコン --}}
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                        {{ mb_substr($update['user'], 0, 1) }}
                                    </div>
                                </div>

                                {{-- 中央：内容 --}}
                                <div class="flex-grow">
                                    <div class="text-sm text-gray-800 mb-1">
                                        <span class="font-bold text-blue-600 cursor-pointer hover:underline">{{ $update['user'] }}</span> さんが
                                        <span class="font-bold text-green-600">{{ $update['action'] }}</span>
                                    </div>
                                    
                                    {{-- 課題リンク風 --}}
                                    <div class="text-sm font-bold text-blue-500 hover:text-blue-700 cursor-pointer mb-1">
                                        {{ $update['title'] }}
                                    </div>

                                    {{-- ステータスバッジや詳細 --}}
                                    <div class="flex items-center space-x-2">
                                        @if(isset($update['status']))
                                            <span class="px-2 py-0.5 text-xs font-bold text-white rounded bg-gray-500">
                                                {{ $update['status'] }}
                                            </span>
                                        @endif
                                        @if(isset($update['detail']))
                                            <span class="text-xs text-gray-500">{{ $update['detail'] }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- 右側：時間・アクション --}}
                                <div class="flex-shrink-0 text-right pl-2">
                                    <div class="text-xs text-gray-400 mb-2">{{ $update['time'] }}</div>
                                    <div class="flex justify-end space-x-3 text-gray-400">
                                        <button class="hover:text-gray-600">💬</button>
                                        <button class="hover:text-yellow-500">★ 0</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 text-center">
                        <button class="text-sm text-gray-500 hover:text-gray-700">もっと見る</button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4 border-b pb-2">📅 今日の予定 ({{ $todayStr }}曜日)</h3>
                    
                    @if(isset($todayCourses) && count($todayCourses) > 0)
                        <ul class="space-y-3 mb-6">
                            @foreach($todayCourses as $course)
                                <li class="flex items-center bg-blue-50 p-3 rounded text-sm">
                                    <span class="font-bold text-blue-800 w-16">{{ $course->period }}限</span>
                                    <span class="flex-1">{{ $course->name }}</span>
                                    <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded">{{ $course->room ?? '教室未定' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 mb-6">今日の授業はありません。</p>
                    @endif

                    <h4 class="font-bold text-md mb-2">🔥 今日の期限</h4>
                    @if(isset($todayTasks) && count($todayTasks) > 0)
                        <ul class="space-y-2">
                            @foreach($todayTasks as $task)
                                <li class="flex items-center justify-between text-sm p-2 border-l-4 border-red-500 bg-red-50">
                                    <span>{{ $task->title }}</span>
                                    <span class="text-red-600 font-bold">今日まで</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">期限切れ間近のタスクはありません。</p>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-4 border-b pb-2">🚀 最優先ミッション</h3>
                    
                    @if(isset($missions) && count($missions) > 0)
                        <ul class="space-y-3 mb-6">
                            @foreach($missions as $mission)
                                <li class="flex items-start">
                                    <input type="checkbox" class="mt-1 mr-2 rounded text-blue-600">
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $mission->title }}</div>
                                        <div class="text-xs text-red-500">期限: {{ \Carbon\Carbon::parse($mission->deadline)->format('m/d') }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 mb-6">優先タスクはありません。素晴らしい！</p>
                    @endif

                    <h4 class="font-bold text-md mb-2">🤖 AI分析: 進捗率</h4>
                    <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700">
                        <div class="bg-green-500 h-4 rounded-full" style="width: {{ $progressPercent ?? 0 }}%"></div>
                    </div>
                    <p class="text-right text-sm text-gray-600 mt-1">{{ $progressPercent ?? 0 }}% 完了</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>