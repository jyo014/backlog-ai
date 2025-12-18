<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between items-center mb-4">
    <a href="{{ route('schedule') }}" class="text-lg font-bold text-gray-800 hover:text-blue-600 hover:underline">
        今週のスケジュール (12/15 - 12/21) 🔗
    </a>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
        ＋ タスクを追加
    </button>
</div>
<div class="flex items-center mb-4">
    <div class="bg-indigo-600 text-white p-2 rounded-full mr-2">
        </div>
    <a href="{{ route('ai-chat') }}" class="text-lg font-bold text-indigo-900 hover:text-indigo-600 hover:underline">
        AIアドバイザーへ移動 🔗
    </a>
    </div>
</x-app-layout>
