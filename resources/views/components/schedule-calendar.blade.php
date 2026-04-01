{{-- resources/views/components/schedule-calendar.blade.php --}}
<div wire:ignore {{ $attributes->merge(['class' => '']) }}>
    @auth
        <!-- 絞り込み用フォーム (ログイン時のみ) -->
        <div class="mb-4">
            <label for="userFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ユーザーで絞り込む</label>
            <select id="userFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                <option value="">全員（自分とフォロー中）</option>
                <option value="{{ auth()->id() }}">自分</option>
                @foreach(Auth::user()->followings as $following)
                    <option value="{{ $following->id }}">{{ $following->name }}</option>
                @endforeach
            </select>
        </div>
    @endauth

    <div id="calendar" class="w-full bg-transparent"></div>
</div>

@pushonce('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css" rel="stylesheet">
    <style>
        /* FullCalendar ダークモード調整 */
        .dark .fc-col-header-cell {
            background-color: #18181b !important; /* zinc-900 */
        }
        .dark .fc-col-header-cell-cushion {
            color: #f4f4f5 !important; /* zinc-100 */
        }
        .dark .fc-theme-standard td, .dark .fc-theme-standard th {
            border-color: #27272a !important; /* zinc-800 */
        }
    </style>
@endpushonce

@pushonce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const userFilter = document.getElementById('userFilter');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                contentHeight: 650,
                events: function(info, successCallback, failureCallback) {
                    let url = '/api/schedules';
                    const params = new URLSearchParams();

                    if (userFilter) {
                        const userId = userFilter.value;
                        if (userId) params.append('user_id', userId);
                    }

                    if (params.toString()) {
                        url += '?' + params.toString();
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => failureCallback(error));
                },

                // カスタムレンダリング: アイコンと名前を表示
                eventContent: function(arg) {
                    const users = arg.event.extendedProps.users || [];
                    const totalUsers = arg.event.extendedProps.total_users || 0;

                    let container = document.createElement('div');
                    container.classList.add('flex', 'flex-col', 'gap-1', 'p-0.5', 'overflow-hidden', 'w-full');

                    users.slice(0, 3).forEach(user => {
                        let userRow = document.createElement('div');
                        userRow.classList.add('flex', 'items-center', 'gap-1.5', 'w-full');

                        // アイコン
                        let img = document.createElement('img');
                        img.src = user.icon || 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y';
                        img.classList.add('w-7', 'h-7', 'rounded-full', 'object-cover', 'border', 'border-gray-200', 'dark:border-zinc-700', 'flex-shrink-0');

                        // 名前
                        let nameSpan = document.createElement('span');
                        nameSpan.textContent = user.name;
                        nameSpan.classList.add('text-[11px]', 'sm:text-xs', 'font-medium', 'text-gray-800', 'dark:text-zinc-200', 'truncate', 'hidden', 'sm:inline');

                        userRow.appendChild(img);
                        userRow.appendChild(nameSpan);
                        container.appendChild(userRow);
                    });

                    if (totalUsers > 3) {
                        let moreDiv = document.createElement('div');
                        moreDiv.textContent = `他${totalUsers - 3}人`;
                        moreDiv.classList.add('text-[10px]', 'text-gray-500', 'dark:text-zinc-400', 'font-bold', 'pl-1');
                        container.appendChild(moreDiv);
                    }

                    return { domNodes: [container] };
                },

                dateClick: function (info) {
                    const selectedDate = info.dateStr;
                    window.location.href = `/schedules/date/${selectedDate}`;
                }
            });

            calendar.render();

            if (userFilter) {
                userFilter.addEventListener('change', function() {
                    calendar.refetchEvents();
                });
            }
        });
    </script>
@endpushonce
