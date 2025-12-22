<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⚙️ 設定
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 保存完了メッセージ --}}
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Backlog連携設定
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Backlogの課題をチームのタスクとして取り込むために設定します。
                            </p>
                        </header>

                        <form method="post" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
                            @csrf

                            {{-- ドメイン入力 --}}
                            <div>
                                <label for="backlog_domain" class="block font-medium text-sm text-gray-700">スペースID (ドメイン)</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        https://
                                    </span>
                                    <input type="text" name="backlog_domain" id="backlog_domain" 
                                        value="{{ old('backlog_domain', $user->backlog_domain) }}"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" 
                                        placeholder="xyz.backlog.jp">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    ご利用のBacklogのURLを入力してください（例: <code>my-company.backlog.jp</code>）
                                </p>
                            </div>

                            {{-- APIキー入力 --}}
                            <div>
                                <label for="backlog_api_key" class="block font-medium text-sm text-gray-700">APIキー</label>
                                <input type="password" name="backlog_api_key" id="backlog_api_key" 
                                    value="{{ old('backlog_api_key', $user->backlog_api_key) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="APIキーを入力">
                                <p class="text-xs text-gray-500 mt-1">
                                    Backlogの「個人設定」→「API」から新しいAPIキーを発行して貼り付けてください。
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    保存する
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
            
            {{-- ダッシュボードに戻るリンク --}}
            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                    &laquo; ダッシュボードに戻る
                </a>
            </div>

        </div>
    </div>
</x-app-layout>