<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="open = true" type="button" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md shadow-sm transition-colors {{ $attributes->get('class') }}">
        {{ $triggerText ?? 'テンプレートを作成' }}
    </button>

    <!-- Modal Background -->
    <div x-show="open" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         x-transition.opacity>
        
        <!-- Modal Content -->
        <div x-show="open" 
             @click.away="open = false"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">テンプレート作成</h3>
                <button @click="open = false" type="button" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('templates.store') }}" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">タイトル</label>
                        <input type="text" name="title" required placeholder="テンプレートのタイトル" class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">本文</label>
                        <textarea name="body" required placeholder="テンプレートの本文" rows="5" class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ジャンル</label>
                        <select name="genre" required class="w-full border border-gray-300 dark:border-gray-600 rounded-md p-2 dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">ジャンルを選択</option>
                            <option value="talk">雑談</option>
                            <option value="game">ゲーム実況</option>
                            <option value="sing">歌枠</option>
                            <option value="event">イベント参加</option>
                            <option value="kikaku">企画</option>
                            <option value="other">その他</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        キャンセル
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition-colors">
                        保存する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
