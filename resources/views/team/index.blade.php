<x-app-layout>
    {{-- ▼▼▼ ケース1：チームに所属している場合（ダッシュボード表示） ▼▼▼ --}}
    @if(isset($team))
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    🏫 {{ $team->name }}
                </h2>
                <span class="text-xs text-gray-500">学科チーム・ポータル</span>
            </div>
        </x-slot>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- 左側：メインタイムライン（課題やお知らせ） --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- 1. チームからのお知らせエリア --}}
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

                        {{-- 2. 直近の課題・提出物 --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div class="flex justify-between items-end mb-4">
                                <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                    <span class="bg-red-100 text-red-600 p-2 rounded-lg mr-3">🔥</span>
                                    提出期限が近い課題
                                </h3>
                                <span class="text-xs text-gray-500">チーム共有タスク</span>
                            </div>

                            {{-- 課題リスト --}}
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

                            {{-- 課題追加フォーム --}}
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

                    {{-- 右側：サイドバー（履修状況） --}}
                    <div class="space-y-6">
                        
                        {{-- メンバー情報 --}}
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
                            <div class="text-sm text-gray-500 mb-2">所属メンバー</div>
                            <div class="flex justify-center -space-x-2 overflow-hidden mb-2">
                                @if(isset($members))
                                    @foreach($members as $member)
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-indigo-600 font-bold text-xs" title="{{ $member->name }}">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                    @endforeach
                                @endif
                                <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-gray-400 text-xs">
                                    +
                                </div>
                            </div>
                            <p class="text-xs text-gray-400">{{ isset($members) ? count($members) : 0 }}名が情報を共有中</p>
                        </div>

                        {{-- あなたの時間割リスト --}}
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

    {{-- ▼▼▼ ケース2：チームにまだ所属していない場合（学校選択フォーム） ▼▼▼ --}}
    @else
        <x-slot name="header"></x-slot>
        <div class="py-12 max-w-4xl mx-auto px-4">
            <div class="bg-white p-8 rounded-2xl shadow text-center">
                <h2 class="text-2xl font-bold mb-6">🏫 所属する大学・チームを選ぼう</h2>
                
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- 既存チームへの参加 --}}
                    <div class="border p-6 rounded-xl">
                        <h3 class="font-bold mb-4">既存のチームに参加</h3>
                        <form action="{{ route('team.join') }}" method="POST">
                            @csrf
                            <select name="team_id" class="w-full border p-2 rounded mb-4">
                                @if(isset($allTeams))
                                    @foreach($allTeams as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                @else
                                    <option disabled>チームが見つかりません</option>
                                @endif
                            </select>
                            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg w-full font-bold hover:bg-blue-700 transition">参加する</button>
                        </form>
                    </div>

                    {{-- 新規チーム作成 --}}
                    <div class="border p-6 rounded-xl bg-gray-50">
                        <h3 class="font-bold mb-4">大学・チームを登録</h3>
                        <form action="{{ route('team.create') }}" method="POST">
                            @csrf
                            <input type="text" name="name" placeholder="大学名・学科名を入力" class="w-full border p-2 rounded mb-4" required>
                            <button class="bg-green-600 text-white px-6 py-2 rounded-lg w-full font-bold hover:bg-green-700 transition">作成して参加</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>