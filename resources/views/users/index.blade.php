<!-- resources/views/users/index.blade.php -->
 <x-layouts.app :title="'ユーザー検索'">
    <h1 class="text-2xl font-bold mb-4">ユーザー検索</h1>
    <form action="{{ route('users.index') }}" method="GET" class="mb-4">
        <input type="text" name="keyword" placeholder="ユーザー名で検索" value="{{ request('keyword') }}"
            class="border rounded px-3 py-1">
        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">検索</button>
    </form>
    <div class="mt-4">
        <h2 class="text-xl font-semibold mb-2">ユーザー一覧</h2>
        @if ($users->count())
            <ul>
                @foreach ($users as $user)
                    <li class="border p-2 rounded mt-3  w-full max-w-md">
                        <a href="{{ route('users.show', $user->id) }}" class="flex space-x-4 text-blue-600 hover:underline">
                        @if($user->profile && $user->profile->icon)
                            <img src="{{ asset('storage/icons/' . $user->profile->icon) }}" alt="アイコン" class="w-12 h-12 rounded-full">
                        @else
                            <div class="w-12 h-12 bg-gray-300 rounded-full"></div> {{-- デフォルトのアイコン代替 --}}
                        @endif
                        {{ $user->name }}</a>
                    </li>
                @endforeach
            </ul>
            {{ $users->appends(['keyword' => request('keyword')])->links() }}
        @else
            <p>ユーザーが見つかりませんでした。</p>
        @endif
    </div>
</x-layouts.app>