<x-layouts.app :title="$date . 'の配信スケジュール'">
    <div class="max-w-4xl mx-auto mt-6 space-y-4">
        <h2 class="text-xl font-semibold">{{ $date }} の配信予定</h2>
        <!-- ゲリラ配信と通常配信を分ける -->
        @php
        $normalSchedules = $schedules->where('is_unscheduled', false);
        $unscheduledSchedules = $schedules->where('is_unscheduled', true);
        @endphp

        @if ($schedules->isEmpty())
        <p>この日に予定されている配信はありません。</p>
        @else
        {{-- 通常配信の描画 --}}
        <ul class="space-y-3 w-full max-w-md">
            @if ($normalSchedules->isNotEmpty())
            <li class="flex items-center mb-6 mt-2">
                <div class="flex-grow border-t-2 border-dashed border-gray-300 dark:border-gray-600"></div>
                <span class="mx-4 px-4 py-1.5 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 text-sm font-bold flex items-center gap-1 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    スケジュール配信
                </span>
                <div class="flex-grow border-t-2 border-dashed border-gray-300 dark:border-gray-600"></div>
            </li>
            @endif

            @foreach ($normalSchedules as $schedule)

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
            {{-- 区切り --}}
            @if ($unscheduledSchedules->isNotEmpty())
            <li class="flex items-center my-8">
                <div class="flex-grow border-t-2 border-dashed border-gray-300 dark:border-gray-600"></div>
                <span class="mx-4 px-4 py-1.5 rounded-full bg-rose-100 text-rose-600 dark:bg-rose-900 dark:text-rose-300 text-sm font-bold flex items-center gap-1 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    ゲリラ配信
                </span>
                <div class="flex-grow border-t-2 border-dashed border-gray-300 dark:border-gray-600"></div>
            </li>
            @endif

            {{-- ゲリラ配信の描画 --}}
            @foreach ($unscheduledSchedules as $schedule)
            <li class="p-4 border rounded shadow">
                <a href="{{route('post.show', $schedule->id)}}">
                    <div class="font-bold text-lg">{{ $schedule->user->name }}</div>
                    <div class="text-sm text-gray-600">
                        時間未定
                    </div>
                    <div class="mt-1">{{ $schedule->title }}</div>
                </a>
            </li>
            @endforeach
        </ul>
        @endif

        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="inline-block mt-4 text-blue-500 underline">← カレンダーに戻る</a>
    </div>
</x-layouts.app>