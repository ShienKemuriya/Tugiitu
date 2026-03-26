@php
/** @var \Illuminate\Database\Eloquent\Collection<\App\Models\PostTemplate> $templates */
    @endphp
    <x-layouts.app :title="'投稿テンプレート管理'">
        <div class="max-w-3xl mx-auto mb-6 flex justify-between items-center sm:px-0 px-4 mt-4">
            <h2 class="text-xl font-bold dark:text-white">テンプレート一覧</h2>
            <x-template-modal trigger-text="＋ 新規作成" />
        </div>
        <ul class="space-y-3 max-w-3xl mx-auto px-4 sm:px-0">
            @foreach ($templates as $template)
            <li class="p-4 border rounded shadow flex justify-between items-center">

                {{-- 左：テンプレ情報 --}}
                <div>
                    <div class="font-bold text-lg">{{ $template->title }}</div>
                    <div class="text-sm text-gray-500">
                        {{ config('genres')[$template->genre] ?? $template->genre }}
                    </div>
                </div>

                {{-- 右：ボタン群 --}}
                <div class="flex gap-3">

                    <a href="{{ route('templates.edit', $template->id) }}" class="text-blue-500 hover:underline">
                        編集
                    </a>

                    {{-- 削除 --}}
                    <form action="{{ route('templates.destroy', $template->id) }}" method="POST"
                        onsubmit="return confirm('削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">
                            削除
                        </button>
                    </form>

                </div>
            </li>
            @endforeach

            @if ($templates->isEmpty())
            <li>テンプレートはまだありません。</li>
            @endif
        </ul>
    </x-layouts.app>