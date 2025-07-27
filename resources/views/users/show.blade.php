<!-- resources/views/users/index.blade.php -->
 <x-layouts.app :title="'ユーザー詳細'">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $user->name }}
    </h2>
    <div class="flex items-center justify-between w-full ">
        @if ($user->profile && $user->profile->icon)
            <img src="{{ asset('storage/icons/' . $user->profile->icon) }}" alt="アイコン" class="w-20 h-20 rounded-full">
        @else
            <div class="w-20 h-20 bg-gray-300 rounded-full"></div>
        @endif
        
        @auth
            @if (auth()->id() !== $user->id)
                @if ($isFollowing)
                    <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="bg-gray-300 px-3 py-1 rounded">フォロー解除</button>
                    </form>
                @else
                    <form action="{{ route('users.follow', $user->id) }}" method="POST">
                        @csrf
                        <button class="bg-blue-500 text-white px-3 py-1 rounded">フォロー</button>
                    </form>
                @endif
            @else
                <a href="{{route('profiles.edit')}}"><flux:button class="bg-teal-700 ">編集</flux:button></a>
            @endif
        @endauth
    </div>
    @auth
        @if (auth()->id() == $user->id)
            <a href="{{ route('users.followings', $user->id) }}" class="text-blue-600 hover:underline">フォロー中のユーザー</a>
        @endif
    @endauth
    <p class="mt-4 max-w-xl break-words whitespace-pre-wrap border">{{ $user->profile->bio ?? '自己紹介はまだありません。' }}</p>
    <div class="mt-10">
        <a href="{{ url("/users") }}" class="btn btn-primary text-blue-500 underline" >配信一覧に戻る</a>
    </div>

</x-layouts.app>