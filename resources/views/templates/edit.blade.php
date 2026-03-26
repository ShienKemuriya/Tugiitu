<x-layouts.app title="テンプレート編集">
    <div class="max-w-2xl mx-auto mt-6">
        <h2 class="text-xl font-semibold mb-4">テンプレート編集</h2>

        <form method="POST" action="{{ route('templates.update', $template->id) }}">
            @csrf
            @method('PATCH')

            <input type="text" name="title" value="{{ old('title', $template->title) }}"
                class="w-full mb-2 border p-2 rounded">

            <textarea name="body" class="w-full mb-2 border p-2 rounded">{{ old('body', $template->body) }}</textarea>

            <select name="genre" class="w-full mb-2 border p-2 rounded">
                <option value="">選択してください</option>
                <option value="talk" {{ $template->genre=='talk' ? 'selected' : '' }}>雑談</option>
                <option value="game" {{ $template->genre=='game' ? 'selected' : '' }}>ゲーム実況</option>
                <option value="sing" {{ $template->genre=='sing' ? 'selected' : '' }}>歌枠</option>
                <option value="event" {{ $template->genre=='event' ? 'selected' : '' }}>イベント参加</option>
                <option value="kikaku" {{ $template->genre=='kikaku' ? 'selected' : '' }}>企画</option>
                <option value="other" {{ $template->genre=='other' ? 'selected' : '' }}>その他</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                更新
            </button>
        </form>

        <a href="{{ route('templates.index') }}" class="inline-block mt-4 text-blue-500 underline">
            ← 戻る
        </a>
    </div>
</x-layouts.app>