<x-app-layout>
    {{--  ケース1：すでにチームに所属している場合（ダッシュボード表示）  --}}
    @if(isset($team))
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    🏫 {{ $team->name }}
                </h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">学科チーム・ポータル</span>
                    {{-- チームを抜けて検索画面に戻るためのボタン --}}
                    <form action="{{ route('team.leave') }}" method="POST" onsubmit="return confirm('チームを抜けて検索画面に戻りますか？');">
                        @csrf
                        <button class="text-xs bg-gray-200 hover:bg-red-100 text-gray-600 hover:text-red-600 px-3 py-1.5 rounded transition font-bold">
                            チームを変更・脱退
                        </button>
                    </form>
                </div>
            </div>
        </x-slot>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- 左側：メインコンテンツ --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- お知らせエリア --}}
                        <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6">
                            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                                <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">📢</span>
                                チーム連絡事項
                            </h3>
                            <div class="space-y-4">
                                <div class="border-l-4 border-indigo-500 pl-4 py-1">
                                    <p class="text-sm text-gray-500">お知らせ</p>
                                    <p class="font-bold text-gray-800">ここにチームの連絡事項が表示されます</p>
                                </div>
                            </div>
                        </div>

                        {{-- 課題リストエリア --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex justify-between items-end mb-4">
                                <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                    <span class="bg-red-100 text-red-600 p-2 rounded-lg mr-3">🔥</span>
                                    提出期限が近い課題
                                </h3>
                                <span class="text-xs text-gray-500">チーム共有タスク</span>
                            </div>

                            @if(isset($teamTasks) && count($teamTasks) > 0)
                                <div class="space-y-3">
                                    @foreach($teamTasks as $task)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition border-l-4 border-red-400">
                                            <div>
                                                <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded">期限</span>
                                                <h4 class="font-bold text-gray-800 mt-1">{{ $task->title }}</h4>
                                                <p class="text-xs text-gray-500 mt-1">期限: {{ $task->deadline }}</p>
                                            </div>
                                            <button class="text-sm bg-white border border-gray-300 text-gray-600 px-3 py-1 rounded hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition">
                                                詳細
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                    現在、迫っている課題はありません🎉
                                </div>
                            @endif

                            {{-- 課題追加アコーディオン --}}
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <details class="group">
                                    <summary class="flex items-center cursor-pointer text-sm text-indigo-600 font-bold hover:text-indigo-800">
                                        <svg class="w-4 h-4 mr-1 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        課題情報をシェアする（追加）
                                    </summary>
                                    <div class="mt-3 bg-gray-50 p-4 rounded-lg">
                                        <form action="{{ route('team.task.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            @csrf
                                            <input type="text" name="title" placeholder="課題名・テスト名" class="md:col-span-2 rounded border-gray-300 text-sm">
                                            <input type="date" name="deadline" class="rounded border-gray-300 text-sm">
                                            <button class="md:col-span-3 bg-indigo-600 text-white py-2 rounded text-sm font-bold hover:bg-indigo-700">追加して共有</button>
                                        </form>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>

                    {{-- 右側：サイドバー --}}
                    <div class="space-y-6">
                        
                        {{-- 追加機能：今日の予定（1日のスケジュール） --}}
                        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white p-6 rounded-xl shadow-lg relative overflow-hidden">
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
                                        <p class="text-xs font-bold text-red-200 mb-2">⚡️ 今日が期限の課題</p>
                                        @foreach($todayTasks as $task)
                                            <div class="flex items-center text-sm bg-red-500/20 p-2 rounded border border-red-400/30">
                                                <span class="mr-2">⚠️</span> {{ $task->title }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- 追加ここまで  --}}

                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
                            <div class="text-sm text-gray-500 mb-2">所属メンバー</div>
                            <div class="flex justify-center -space-x-2 overflow-hidden mb-2">
                                @foreach($members as $member)
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 font-bold text-xs" title="{{ $member->name }}">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                @endforeach
                                <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-gray-400 text-xs">+</div>
                            </div>
                            <p class="text-xs text-gray-400">{{ count($members) }}名が情報を共有中</p>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-800">🎓 履修中の授業</h3>
                                <a href="{{ route('courses.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">編集</a>
                            </div>
                            @if(isset($myCourses) && count($myCourses) > 0)
                                <ul class="space-y-2">
                                    @foreach($myCourses as $course)
                                        <li>
                                            <a href="{{ route('courses.show', $course) }}" class="flex items-center justify-between p-2 rounded hover:bg-indigo-50 transition group">
                                                <div class="flex items-center">
                                                    <span class="text-xs font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded mr-2 group-hover:bg-indigo-200 group-hover:text-indigo-700">
                                                        {{ $course->day_of_week }}{{ $course->period }}
                                                    </span>
                                                    <span class="text-sm text-gray-700 font-medium">{{ $course->course_name }}</span>
                                                </div>
                                                <span class="text-gray-300 group-hover:text-indigo-400">→</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-6">
                                    <p class="text-sm text-gray-400 mb-3">授業が登録されていません</p>
                                    <a href="{{ route('courses.index') }}" class="inline-block bg-indigo-600 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                                        履修登録へ行く
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- ケース2：まだチームに入っていない場合（検索・参加画面） --}}
    @else
        <x-slot name="header"></x-slot>
        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- ヘッダーメッセージ --}}
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-800 mb-2">🏫 チームに参加しよう</h2>
                    <p class="text-gray-500">あなたの大学・学科のチームを検索して、情報共有に参加しましょう。</p>
                </div>

                {{-- ① 検索フォーム --}}
                <div class="max-w-xl mx-auto mb-10 relative z-10">
                    <form action="{{ route('team.index') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full pl-12 pr-24 py-4 rounded-full border-2 border-indigo-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 shadow-lg text-lg transition placeholder-gray-400"
                            placeholder="例：福岡工業大学 情報工学科">
                        
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-indigo-600 text-white px-6 rounded-full font-bold hover:bg-indigo-700 transition shadow-md flex items-center">
                            検索
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="grid md:grid-cols-5 min-h-[400px]">
                        
                        {{-- ② 検索結果リスト（左側） --}}
                        <div class="md:col-span-3 p-8 border-b md:border-b-0 md:border-r border-gray-100 bg-white">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-xl text-gray-800 flex items-center">
                                    <span class="mr-2">🔍</span> 見つかったチーム
                                </h3>
                                @if(request('search'))
                                    <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold">{{ count($allTeams) }}件ヒット</span>
                                @endif
                            </div>

                            <div class="space-y-3 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                                @forelse($allTeams as $t)
                                    <div class="group bg-white border border-gray-200 p-4 rounded-xl hover:shadow-md hover:border-indigo-300 transition flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-lg group-hover:text-indigo-600 transition">{{ $t->name }}</h4>
                                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                    {{ $t->users ? $t->users->count() : 0 }}人
                                                </span>
                                                <span>{{ $t->created_at->format('Y/m/d') }} 開設</span>
                                            </div>
                                        </div>
                                        <form action="{{ route('team.join') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="team_id" value="{{ $t->id }}">
                                            <button class="bg-gray-100 text-gray-600 hover:bg-indigo-600 hover:text-white px-5 py-2 rounded-lg text-sm font-bold transition">
                                                参加する
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="h-64 flex flex-col items-center justify-center text-center text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        @if(request('search'))
                                            <p class="font-bold text-gray-500">「{{ request('search') }}」は見つかりません</p>
                                            <p class="text-xs mt-1">右側のフォームから新しく作成してください 👉</p>
                                        @else
                                            <p>まずは大学名や学科名で検索！</p>
                                        @endif
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- ③ 新規作成フォーム（右側） --}}
                        <div class="md:col-span-2 bg-gradient-to-br from-indigo-50 to-white p-8 flex flex-col justify-center">
                            <div class="bg-white/80 backdrop-blur p-6 rounded-2xl shadow-sm border border-indigo-100">
                                <div class="mb-6">
                                    <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded-full mb-2 inline-block">New</span>
                                    <h3 class="font-bold text-xl text-indigo-900 mb-2">新しいチームを作る</h3>
                                    <p class="text-xs text-indigo-600 leading-relaxed">
                                        自分の学科が見つからない場合は、ここから新しく作成して友達を招待しましょう！
                                    </p>
                                </div>
                                
                                <form action="{{ route('team.create') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">大学・学科名</label>
                                        <input type="text" name="name" 
                                            placeholder="例：〇〇大学 情報工学科" 
                                            value="{{ request('search') }}"
                                            class="w-full border-gray-200 bg-gray-50 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                                    </div>
                                    <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 transition duration-200 flex justify-center items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        作成して参加
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>