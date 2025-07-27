{{-- FullCalendar(JS)を使ったコード --}}

<x-layouts.app :title="__('Dashboard')">
    <div wire:ignore>
        <div id="calendar"></div>
    </div>
</x-layouts.app>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>

<div id="calendar"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'ja',
            events: '/api/schedules',

            //リンクを埋め込む
            dateClick: function(info) {
                const selectedDate = info.dateStr; // YYYY-MM-DD形式
                const url = `/schedules/date/${selectedDate}`; // Laravel側で定義したルート
                window.location.href = url; // 詳細ページへ遷移
            }
        });

        calendar.render();
    });
</script>
