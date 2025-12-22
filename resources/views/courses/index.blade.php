<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('大学授業 Wiki') }}
            </h2>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">みんなで情報共有</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-indigo-100 border-l-4 border-indigo-500 text-indigo-700 p-4 mb-6 shadow-sm rounded-r">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 授業新規登録フォーム --}}
            <div class="mb-8">
                <details class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 open:ring-2 open:ring-indigo-100 transition-all duration-300">
                    <summary class="flex items-center justify-between p-6 cursor-pointer list-none hover:bg-gray-50 transition">
                        <h4 class="font-bold text-lg text-gray-800 flex items-center">
                            <span class="bg-indigo-600 text-white p-1.5 rounded-lg mr-3 shadow-md shadow-indigo-200">＋</span>
                            新しい授業を登録する
                        </h4>
                        <span class="text-gray-400 group-open:rotate-180 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    
                    <div class="px-6 pb-6 pt-2 border-t border-gray-100">
                        <form action="{{ route('courses.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                                {{-- 授業名 --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">授業名 <span class="text-red-500">*</span></label>
                                    <input type="text" name="course_name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 placeholder-gray-300" placeholder="例：情報工学概論">
                                </div>

                                {{-- 大学名  --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">大学名 <span class="text-red-500">*</span></label>
                                    <input type="text" name="university_name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 placeholder-gray-300" placeholder="例：〇〇大学">
                                </div>

                                {{-- 担当の先生 (新規要望) --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">担当教員 <span class="text-red-500">*</span></label>
                                    <input type="text" name="teacher_name" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 placeholder-gray-300" placeholder="例：山田 太郎 先生">
                                </div>

                                {{-- 曜日 --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">曜日 <span class="text-red-500">*</span></label>
                                    <select name="day_of_week" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="" disabled selected>選択してください</option>
                                        @foreach(['月', '火', '水', '木', '金', '土'] as $day)
                                            <option value="{{ $day }}">{{ $day }}曜日</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 時限 --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">時限 <span class="text-red-500">*</span></label>
                                    <select name="period" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="" disabled selected>選択してください</option>
                                        @foreach(range(1, 7) as $period)
                                            <option value="{{ $period }}">{{ $period }}限</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-xl hover:bg-indigo-700 transition font-bold shadow-md shadow-indigo-200 flex items-center inline-flex">
                                    <span>登録する</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </details>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">📚</span>
                            提供されている授業一覧
                        </h3>
                        <span class="text-xs text-gray-400">{{ count($courses) }} classes available</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($courses as $course)
                            <div class="group relative bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-indigo-300">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-1">
                                            {{ $course->day_of_week }}曜 {{ $course->period }}限
                                        </p>
                                        <h4 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition">
                                            <a href="{{ route('courses.show', $course) }}">
                                                <span class="absolute inset-0"></span> {{ $course->course_name }}
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-400 mt-1">{{ $course->university_name }}</p>
                                    </div>
                                    
                                    @if(in_array($course->id, $myCourseIds))
                                        <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-1 rounded-full border border-green-200">
                                            履修中
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-4 flex items-center justify-between relative z-10">
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ $course->users->count() }} students
                                    </span>

                                    @if(in_array($course->id, $myCourseIds))
                                        <form action="{{ route('courses.detach', $course) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-gray-100 hover:bg-red-100 text-gray-500 hover:text-red-600 px-3 py-1.5 rounded-lg transition border border-gray-200">
                                                解除
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs bg-indigo-600 text-white px-4 py-1.5 rounded-lg shadow hover:bg-indigo-700 transition font-bold">
                                                + 履修登録
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
    </div>
</x-app-layout>