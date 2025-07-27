<x-layouts.app :title="'詳細画面'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-4 sm:p-8">
            <div class="px-10 mt-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    投稿の個別表示
                </h2>

                <x-message :message="session('message')" type="success" />
                <div class="bg-white w-full  rounded-2xl px-10 py-8 shadow-lg hover:shadow-2xl transition duration-500">
                    <div class="mt-4">
                        <p class="text-lg font-semibold">
                            {{ $post->title }}
                        </p>
                    </div>
                    <hr class="w-full">
                    <p>開始日時：{{ \Carbon\Carbon::parse($post->start_time)->format('Y年m月d日 H時i分') }}</p>
                    @auth
                        @if(auth()->id() === $post->user_id)
                            <a href="{{route('post.edit', $post)}}"><flux:button class="bg-teal-700 float-right">編集</flux:button></a>
                        @endif
                    @endauth
                    {{-- ジャンルに応じて表示を変える --}}
                    @if ($post->genre == 'talk')
                        <p>ジャンル：雑談</p>
                    @elseif($post->genre == 'game')
                        <p>ジャンル：ゲーム配信</p>
                    @elseif($post->genre == 'sing')
                        <p>ジャンル：歌枠</p>
                    @elseif($post->genre == 'event')
                        <p>ジャンル：イベント参加</p>
                    @elseif($post->genre == 'kikaku')
                        <p>ジャンル：企画</p>
                    @else
                        <p>ジャンル：その他</p>                    
                    @endif
                    <div class="mt-4">
                        <p>コメント</p>
                        <p class="text-gray-600 whitespace-pre-line">{{ $post->body }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
    $date = \Carbon\Carbon::parse($post->start_time)->format('Y-m-d');
    @endphp

<a href="{{ url("/schedules/date/{$date}") }}" class="btn btn-primary text-blue-500 underline" >配信一覧に戻る</a>

</x-layouts.app>