<x-layouts.app :title="'プロフィール'">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $user->name }}
    </h2>
    <div class="flex items-center justify-between w-full ">
        @if($profile->icon)
            <img src="{{ asset('storage/icons/' . $profile->icon) }}" width="100">
        @else
            <div class="w-12 h-12 bg-gray-300 rounded-full"></div> 
        @endif
        @auth
            @if (auth()->id() == $user->id)
                <a href="{{route('profiles.edit')}}"><flux:button class="bg-teal-700 ">編集</flux:button></a>
            @endif
        @endauth
    </div>
     <a href="{{ route('users.followings', $user->id) }}" class="text-blue-600 hover:underline">フォロー中のユーザー</a>
    <p class="mt-4 max-w-xl break-words whitespace-pre-wrap border">{{ $profile->bio }}</p>
</x-layouts.app>