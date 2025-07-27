 <x-layouts.app>
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-4 sm:p-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                配信時間の編集
            </h2>

            <x-message :message="session('message')"  type="success"/>
            <x-message :message="$errors->all()" type="error" />

            <form  method="post" action="{{route('post.update', $post)}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="md:flex items-center mt-8">
                    <div class="w-full flex flex-col">
                        <label for="title" class="font-semibold leading-none mt-4">配信タイトル</label>
                        <input type="text" name="title"
                            class="mt-1 w-auto py-2 pl-2 placeholder-gray-300 border border-gray-300 rounded-md" id="title"
                            value="{{ old('title', $post->title) }}">
                    </div>
                </div>
                <div class="md:flex items-center mt-8">
                    <div class="w-full flex flex-col">
                        <label for="start_time" class="font-semibold leading-none mt-4" >配信日時</label>
                        <input type="datetime-local"
                            name="start_time"
                            id="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($post->start_time)->format('Y-m-d\TH:i')) }}"
                            class="mt-1 w-auto py-2 pl-2 border border-gray-300 rounded-md placeholder-gray-300">
                    </div>
                </div>

                <div class="md:flex items-center mt-8">
                    <div class="w-full flex flex-col">
                        <label for="genre" class="font-semibold leading-none mt-4">配信ジャンル</label>
                        <select name="genre" id="genre"
                            class="mt-1 w-auto py-2 pl-2 border border-gray-300 rounded-md placeholder-gray-300">
                            <option value="">選択してください</option>
                            <option value="talk" {{ old('genre', $post->genre) == 'talk' ? 'selected' : '' }}>雑談</option>
                            <option value="game" {{ old('genre', $post->genre) == 'game' ? 'selected' : '' }}>ゲーム実況</option>
                            <option value="sing" {{ old('genre', $post->genre) == 'sing' ? 'selected' : '' }}>歌枠</option>
                            <option value="event"  {{ old('genre', $post->genre) == 'event' ? 'selected' : '' }}>イベント参加</option>
                            <option value="kikaku" {{ old('genre', $post->genre) == 'kikaku' ? 'selected' : '' }}>企画</option>
                            <option value="other" {{ old('genre', $post->genre) == 'other' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>
                </div>

                <div class="w-full flex flex-col">
                    <label for="body" class="font-semibold leading-none mt-4">コメント</label>
                    <textarea name="body" class="mt-1 w-auto py-2 pl-2 border border-gray-300 rounded-md" id="body" cols="30"
                        rows="5">{{old('body', $post->body)}}</textarea>
                </div>


                <flux:button variant="primary" type="submit" class="w-full mt-4">更新</flux:button>
            </form>

            <form method="post" action="{{ route('post.destroy', $post) }}"
                class="mt-10">
                @csrf
                @method('delete')
                <flux:button variant="danger" class="bg-red-700 float-right ml-4" type="submit"
                    onClick="return confirm('本当に削除しますか？');">削除</flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>