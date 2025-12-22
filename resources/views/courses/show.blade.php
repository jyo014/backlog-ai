<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->course_name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('courses.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center">
                    ← 授業一覧に戻る
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r shadow-sm">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r shadow-sm">
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-extrabold text-gray-800">{{ $course->course_name }}</h3>
                        <p class="text-gray-500 mt-1 flex items-center">
                            <span class="bg-gray-100 px-2 py-0.5 rounded text-xs font-bold mr-2">{{ $course->university_name }}</span>
                            {{ $course->day_of_week }}曜 {{ $course->period }}限
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block">履修者数</span>
                        <span class="text-xl font-bold text-indigo-600">{{ $course->users->count() }}</span>
                        <span class="text-xs text-gray-400">人</span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 mb-6">
                <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg mr-2 text-sm">📢</span>
                    情報を共有する
                </h4>
                <form action="{{ route('courses.tasks.store', $course) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">タイトル</label>
                            <input type="text" name="title" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="例：第1回レポート">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">期限・実施日</label>
                            <input type="datetime-local" name="due_date" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">詳細メモ</label>
                        <textarea name="description" rows="2" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="範囲や提出場所など..."></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 transition font-bold shadow-md shadow-indigo-200">
                            追加する
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <h4 class="font-bold text-gray-600 ml-1">📋 みんなの共有リスト</h4>
                @if($course->courseTasks->count() > 0)
                    @foreach($course->courseTasks as $task)
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4">
                            <div class="bg-red-50 text-red-500 p-3 rounded-xl shrink-0 font-bold text-center min-w-[60px]">
                                <span class="text-xs block">期限</span>
                                <span class="text-sm">{{ \Carbon\Carbon::parse($task->due_date)->format('m/d') }}</span>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-800 text-lg">{{ $task->title }}</h5>
                                <p class="text-gray-600 text-sm mt-1">{{ $task->description }}</p>
                                <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                                    <span>Added by {{ $task->user->name ?? 'Unknown' }}</span>
                                    <span>{{ $task->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-300 text-gray-400">
                        まだ情報はありません。<br>最初の情報を追加してみよう！
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>