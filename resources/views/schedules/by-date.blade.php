<x-layouts.app :title="$date . 'の配信スケジュール'">
    <div class="max-w-4xl mx-auto mt-6 space-y-4">
        <h2 class="text-xl font-semibold">{{ $date }} の配信予定</h2>

        @if ($schedules->isEmpty())
            <p>この日に予定されている配信はありません。</p>
        @else
            <ul class="space-y-3 w-full max-w-md">
                @foreach ($schedules as $schedule)
                    <li class="p-4 border rounded shadow">
                        <a href="{{route('post.show', $schedule->id)}}">
                        <div class="font-bold text-lg">{{ $schedule->user->name }}</div>
                        <div class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}〜
                        </div>
                        <div class="mt-1">{{ $schedule->title }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-blue-500 underline">← カレンダーに戻る</a>
    </div>
</x-layouts.app>
