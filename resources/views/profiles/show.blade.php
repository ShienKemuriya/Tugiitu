<x-layouts.app :title="'プロフィール'">
    <div class="max-w-3xl mx-auto space-y-6 py-6 border-b border-transparent">
        
        <!-- ヘッダー部分 (名前と編集ボタン) -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $user->name }}
            </h2>
            @auth
                @if (auth()->id() == $user->id)
                    <a href="{{route('profiles.edit')}}">
                        <flux:button class="bg-teal-700 hover:bg-teal-800 text-white transition-colors">プロフィールを編集</flux:button>
                    </a>
                @endif
            @endauth
        </div>

        <!-- プロフィールカード -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row gap-6 sm:gap-8 items-start">
                
                <!-- アイコン -->
                <div class="flex-shrink-0">
                    @if($profile->icon)
                        <img src="{{ asset('storage/icons/' . $profile->icon) }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md" alt="アイコン">
                    @else
                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-md" alt="デフォルトアイコン">
                    @endif
                </div>

                <!-- 詳細情報 -->
                <div class="flex-grow w-full space-y-5">
                    
                    <!-- フォロー中のユーザーリンク -->
                    <div>
                        <a href="{{ route('users.followings', $user->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 rounded-lg text-sm font-medium transition-colors border border-gray-200 dark:border-gray-600">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-200">フォロー中のユーザー</span>
                            <svg class="w-4 h-4 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    
                    <!-- 自己紹介文 -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">自己紹介</p>
                        @if($profile->bio)
                            <p class="text-gray-700 dark:text-gray-200 text-sm sm:text-base leading-relaxed break-words whitespace-pre-wrap">{{ $profile->bio }}</p>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 text-sm italic">自己紹介が設定されていません</p>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>