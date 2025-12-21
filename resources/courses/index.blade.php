<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                大学授業一覧
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                ← ダッシュボードに戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8 border-2 border-indigo-100">
                <h3 class="text-lg font-bold mb-4 text-indigo-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    新しい授業を登録する
                </h3>
                
                <form action="{{ route('courses.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">曜日</label>
                        <select name="day_of_week" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach(['月', '火', '水', '木', '金', '土'] as $day)
                                <option value="{{ $day }}">{{ $day }}曜日</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">時限</label>
                        <select name="period" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @foreach(range(1, 6) as $p)
                                <option value="{{ $p }}">{{ $p }}限</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">大学名</label>
                        <input type="text" name="university_name" placeholder="例: 福岡工業大学" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">担当教員名</label>
                        <input type="text" name="teacher_name" placeholder="例: 山田 太郎" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">授業科目名 <span class="text-red-500">*</span></label>
                        <input type="text" name="course_name" required placeholder="例: 基礎ロボット工学" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="md:col-span-2 lg:col-span-1">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700 transition shadow-sm">
                            授業を登録
                        </button>
                    </div>
                </form>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">提供されている授業一覧</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($courses as $course)
                        <div class="border rounded-lg p-4 hover:shadow-md transition relative bg-white flex flex-col h-full">
                            <div class="absolute top-3 right-3 text-xs font-bold text-indigo-700 bg-indigo-100 px-2 py-1 rounded">
                                {{ $course->day_of_week }} / {{ $course->period }}限
                            </div>

                            <div class="mb-4 pr-16">
                                <h4 class="text-xl font-bold text-gray-800 leading-tight">
                                    <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:underline">
                                        {{ $course->course_name }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $course->university_name }}</p>
                                
                                <p class="text-sm text-gray-600 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $course->teacher_name ?? '担当教員未定' }}
                                </p>
                            </div>
                            
                            <div class="mt-auto pt-4 border-t flex justify-between items-center">
                                @if(in_array($course->id, $myCourseIds))
                                    <span class="text-sm text-green-600 font-bold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        履修中
                                    </span>
                                    <form action="{{ route('courses.detach', $course) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 underline">
                                            解除
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('courses.enroll', $course) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 text-sm font-bold transition">
                                            履修登録
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>