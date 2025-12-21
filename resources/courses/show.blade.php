<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $course->course_name }} の詳細
            </h2>
            <a href="{{ route('courses.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                ← 一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1">
                    <div class="bg-white shadow sm:rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">授業情報</h3>
                        <p class="mb-2"><span class="font-bold">大学名:</span> {{ $course->university_name ?? '未登録' }}</p>
                        <p class="mb-2"><span class="font-bold">時間:</span> {{ $course->day_of_week }}曜 {{ $course->period }}限</p>
                    </div>

                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">➕ 課題・テストを追加</h3>
                        <form action="{{ route('courses.tasks.store', $course) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">タイトル</label>
                                <input type="text" name="title" required placeholder="例: 中間テスト、レポートNo.1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">日付・期限</label>
                                <input type="date" name="due_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">詳細・メモ</label>
                                <textarea name="description" rows="3" placeholder="範囲: P.30~50 など" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                                追加する
                            </button>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-6">📅 課題・テスト一覧</h3>

                        @if($course->courseTasks->isEmpty())
                            <p class="text-gray-500 text-center py-8">まだ登録された課題やテストはありません。</p>
                        @else
                            <div class="space-y-4">
                                @foreach($course->courseTasks as $task)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50 flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-lg font-bold text-gray-800">{{ $task->title }}</h4>
                                                @if(\Carbon\Carbon::parse($task->due_date)->isPast())
                                                    <span class="bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded">終了</span>
                                                @else
                                                    <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded">予定</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                📅 {{ \Carbon\Carbon::parse($task->due_date)->format('Y年m月d日') }}
                                            </p>
                                            @if($task->description)
                                                <p class="text-sm text-gray-500 bg-gray-100 p-2 rounded">
                                                    {!! nl2br(e($task->description)) !!}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="text-xs text-gray-400">
                                            登録日: {{ $task->created_at->format('m/d') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>