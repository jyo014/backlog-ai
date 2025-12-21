<x-app-layout>
    <x-slot name="header"></x-slot>
    <div class="py-12 max-w-4xl mx-auto px-4">
        <div class="bg-white p-8 rounded-2xl shadow text-center">
            <h2 class="text-2xl font-bold mb-6">🏫 所属する大学・チームを選ぼう</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="border p-6 rounded-xl">
                    <h3 class="font-bold mb-4">既存のチームに参加</h3>
                    <form action="{{ route('team.join') }}" method="POST">
                        @csrf
                        <select name="team_id" class="w-full border p-2 rounded mb-4">
                            @foreach($allTeams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg w-full">参加する</button>
                    </form>
                </div>

                <div class="border p-6 rounded-xl bg-gray-50">
                    <h3 class="font-bold mb-4">大学・チームを登録</h3>
                    <form action="{{ route('team.create') }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="大学名などを入力" class="w-full border p-2 rounded mb-4" required>
                        <button class="bg-green-600 text-white px-6 py-2 rounded-lg w-full">作成して参加</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>