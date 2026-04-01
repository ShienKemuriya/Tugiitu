<x-layouts.app :title="__('ライバースケジュールへようこそ')">
    <div class="flex flex-col items-center justify-center text-center mt-10">
        <h1 class="text-2xl font-bold mb-4">ライバースケジュールへようこそ</h1>
        <p class="mb-6 max-w-md text-sm text-gray-600 dark:text-gray-300">
            このアプリは、ライバーの配信スケジュールを簡単に管理・共有するためのツールです。登録して、あなたのスケジュールをすばやく管理しましょう。
        </p>

        @guest
        <a href="{{ route('register') }}"
            class="px-6 py-2 bg-[#1b1b18] text-white text-sm rounded hover:bg-[#3e3e3a] transition mb-8">
            無料で始める
        </a>
        @else
        <a href="{{ url('/dashboard') }}"
            class="px-6 py-2 bg-[#1b1b18] text-white text-sm rounded hover:bg-[#3e3e3a] transition mb-8">
            ダッシュボードへ
        </a>
        @endguest

        <!-- カレンダー -->
        <div class="w-full mt-2 lg:max-w-4xl max-w-[335px] mx-auto">
            <x-schedule-calendar />
        </div>
    </div>
</x-layouts.app>