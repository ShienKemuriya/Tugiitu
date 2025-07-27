<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>ライバースケジュール</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

        <!-- ヘッダー -->
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- メインメッセージ -->
        <main class="text-center flex flex-col items-center">
            <h1 class="text-2xl font-bold mb-4">ライバースケジュールへようこそ</h1>
            <p class="mb-6 max-w-md text-sm text-gray-600 dark:text-gray-300">
                このアプリは、ライバーの配信スケジュールを簡単に管理・共有するためのツールです。登録して、あなたのスケジュールをすばやく管理しましょう。
            </p>

            @guest
                <a href="{{ route('register') }}" class="px-6 py-2 bg-[#1b1b18] text-white text-sm rounded hover:bg-[#3e3e3a] transition">
                    無料で始める
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-[#1b1b18] text-white text-sm rounded hover:bg-[#3e3e3a] transition">
                    ダッシュボードへ
                </a>
            @endguest
        </main>

        <!-- フッター用スペース -->
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

    </body>
</html>
