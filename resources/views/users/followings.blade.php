<x-layouts.app :title="'フォロー中のユーザー'">
    <h2 class="text-xl font-bold mb-4">フォロー中のユーザー</h2>

    @if ($followings->isEmpty())
        <p>フォロー中のユーザーはいません。</p>
    @else
        <ul class="space-y-2 w-full max-w-md">
            @foreach ($followings as $user)
                <li class="border p-2 rounded">
                    <a href="{{ route('users.show', $user->id) }}" class="flex space-x-4 text-blue-600 hover:underline">
                        @if($user->profile && $user->profile->icon)
                            <img src="{{ asset('storage/icons/' . $user->profile->icon) }}" alt="アイコン" class="w-12 h-12 rounded-full">
                        @else
                            <div class="w-12 h-12 bg-gray-300 rounded-full"></div> {{-- デフォルトのアイコン代替 --}}
                        @endif
                        {{ $user->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="mt-6">
        <a href="{{ url('/profile') }}" class="text-sm text-gray-500 hover:underline">プロフィールに戻る</a>
    </div>
</x-layouts.app>
