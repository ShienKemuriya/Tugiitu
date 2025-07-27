<x-layouts.app >
    @include('partials.settings-heading')
    <x-settings.layout :title="'プロフィール編集'">
        @if(session('success'))
            <div class="text-green-500">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <label>アイコン画像</label><br>
                @if($profile->icon)
                    <img src="{{ asset('storage/icons/' . $profile->icon) }}" width="100">
                @endif
                <input type="file" name="icon" class="block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="mt-4">
                <label>プロフィール</label><br>
                <textarea name="bio" rows="5" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('bio', $profile->bio) }}</textarea>
            </div>

            <div class="mt-4">
                <button class="bg-blue-500 text-white px-4 py-2 rounded">更新する</button>
            </div>
        </form>
    </x-settings.layout>
</x-layouts.app>