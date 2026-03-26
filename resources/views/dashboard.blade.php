{{-- FullCalendar(JS)を使ったコード --}}

<x-layouts.app :title="__('Dashboard')">
    <!-- 絞り込み用フォーム -->
    <div class="mb-4">
        <label for="userFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ユーザーで絞り込む</label>
        <select id="userFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
            <option value="">全員（自分とフォロー中）</option>
            <option value="{{ auth()->id() }}">自分</option>
            @foreach(auth()->user()->followings as $following)
                <option value="{{ $following->id }}">{{ $following->name }}</option>
            @endforeach
        </select>
    </div>

    <div wire:ignore class="mt-4">
        <div id="calendar"></div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const userFilter = document.getElementById('userFilter');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                events: function(info, successCallback, failureCallback) {
                    const userId = userFilter.value;
                    let url = '/api/schedules';
                    if (userId) {
                        url += '?user_id=' + userId;
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
                },

                //リンクを埋め込む
                dateClick: function (info) {
                    const selectedDate = info.dateStr; // YYYY-MM-DD形式
                    const url = `/schedules/date/${selectedDate}`; // Laravel側で定義したルート
                    window.location.href = url; // 詳細ページへ遷移
                }
            });

            calendar.render();

            userFilter.addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
</x-layouts.app>