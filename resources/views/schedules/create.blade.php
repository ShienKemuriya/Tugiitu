<x-layouts.app :title="'配信時間の投稿'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-4 sm:p-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                配信時間の投稿
            </h2>
            <x-message :message="session('message')" type="success" />
            <x-message :message="$errors->all()" type="error" />


            {{-- 投稿テンプレートの選択と作成 --}}
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-3">
                    <label for="templateSelect" class="font-semibold text-gray-700 dark:text-gray-300">テンプレートから引用</label>
                    <x-template-modal trigger-text="新しいテンプレートを作成" />
                </div>
                <select id="templateSelect" class="w-full py-2 pl-3 pr-10 border border-gray-300 rounded-md dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">テンプレートを選択</option>

                    @foreach ($templates as $template)
                    <option value="{{ $template->id }}" data-title="{{ $template->title }}"
                        data-body="{{ $template->body }}" data-genre="{{ $template->genre }}">
                        {{ $template->title }}
                    </option>
                    @endforeach
                </select>
            </div>

            <form method="post" action="{{route('post.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="md:flex items-center mt-8">
                    <div class="w-full flex flex-col">
                        <label for="title" class="font-semibold leading-none mt-4">配信タイトル</label>
                        <input type="text" name="title"
                            class="mt-1 w-auto py-2 pl-2 placeholder-gray-300 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300"
                            id="title" value="{{old('title')}}">
                    </div>
                </div>

                <div class="md:flex items-center mt-8">
                    <div class="w-full flex flex-col">
                        <label for="start_time" class="font-semibold leading-none mt-4">配信日時</label>

                        <input type="datetime-local" name="start_time" id="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                            class="mt-1 w-auto py-2 pl-2 border border-gray-300 rounded-md placeholder-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">

                        <label class="mt-2 text-sm">
                            <input type="checkbox" name="is_unscheduled" id="is_unscheduled" value="1" {{
                                old('is_unscheduled') ? 'checked' : '' }}>
                            時間未定（ゲリラ配信）
                        </label>
                    </div>
                </div>

                <div class="md:flex items-center mt-8">
                    <div class="w-full flex flex-col">
                        <label for="genre" class="mt-1 font-semibold leading-none mt-4">配信ジャンル</label>
                        <select name="genre" id="genre"
                            class="w-auto py-2 pl-2 border border-gray-300 rounded-md placeholder-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                            <option value="">選択してください</option>
                            <option value="talk" {{ old('genre')=='talk' ? 'selected' : '' }}>雑談</option>
                            <option value="game" {{ old('genre')=='game' ? 'selected' : '' }}>ゲーム実況</option>
                            <option value="sing" {{ old('genre')=='sing' ? 'selected' : '' }}>歌枠</option>
                            <option value="event" {{ old('genre')=='event' ? 'selected' : '' }}>イベント参加</option>
                            <option value="kikaku" {{ old('genre')=='kikaku' ? 'selected' : '' }}>企画</option>
                            <option value="other" {{ old('genre')=='other' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>
                </div>

                <div class="w-full flex flex-col">
                    <label for="body" class="font-semibold leading-none mt-4">コメント</label>
                    <textarea name="body"
                        class="mt-1 w-auto py-2 pl-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300"
                        id="body" cols="30" rows="5">{{old('body')}}</textarea>
                </div>


                <flux:button variant="primary" type="submit" class="w-full mt-4">投稿</flux:button>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const checkbox = document.getElementById('is_unscheduled');
                        const datetime = document.getElementById('start_time');

                        function toggle() {
                            if (checkbox.checked) {
                                // ゲリラ → 日付のみ
                                datetime.type = 'date';

                                // 時間部分を削る
                                if (datetime.value) {
                                    datetime.value = datetime.value.split('T')[0];
                                }
                            } else {
                                // 通常 → 日付＋時間
                                datetime.type = 'datetime-local';

                                // 時刻を補完（空なら現在時刻）
                                if (datetime.value && !datetime.value.includes('T')) {
                                    datetime.value = datetime.value + 'T00:00';
                                }
                            }
                        }

                        checkbox.addEventListener('change', toggle);
                        toggle();
                    });
                </script>
            </form>
    </div>
    <script>
        document.getElementById('templateSelect').addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];

            if (!selected.value) return;

            if (!confirm('現在の入力内容を上書きしますか？')) return;

            document.getElementById('title').value = selected.dataset.title || '';
            document.getElementById('body').value = selected.dataset.body || '';
            document.getElementById('genre').value = selected.dataset.genre || '';
        });
    </script>

</x-layouts.app>