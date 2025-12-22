@props(['task'])

<div draggable="true" data-id="{{ $task->id }}" class="task-card bg-white p-4 rounded-lg shadow-sm border border-gray-200 cursor-move hover:shadow-md transition group relative mb-3">
    
    {{-- Backlogタグ --}}
    @if(str_contains($task->title, '[Backlog]'))
        <span class="absolute top-3 right-3 text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded border border-green-200 font-bold">Backlog</span>
    @endif

    <h4 class="font-bold text-gray-800 text-sm mb-2 pr-12 leading-snug">{{ str_replace('[Backlog] ', '', $task->title) }}</h4>
    
    <div class="flex justify-between items-end mt-2">
        <div class="text-xs text-gray-500 flex flex-col gap-1">
            <span class="flex items-center gap-1 {{ \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status != 'done' ? 'text-red-500 font-bold' : '' }}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::parse($task->deadline)->format('Y/m/d') }}
            </span>
        </div>
        
        {{-- 担当者アイコン（自分のイニシャル） --}}
        <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-[10px] text-indigo-600 font-bold border border-indigo-200">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
    </div>
</div>