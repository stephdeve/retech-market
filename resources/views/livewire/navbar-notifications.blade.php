<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button type="button" @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg transition-colors duration-200">
        <!-- Bell Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        <!-- Notification Badge -->
        @if($this->unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white dark:border-gray-900">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 w-80 mt-2 origin-top-right bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden"
         style="display: none;">
        
        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Notifications</span>
                @if($this->unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead" class="text-xs text-brand-600 dark:text-brand-400 hover:text-brand-800 dark:hover:text-brand-300 transition duration-150">
                        Tout marquer comme lu
                    </button>
                @endif
            </div>

            <div class="max-h-64 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <div wire:click="markAsRead('{{ $notification->id }}')" class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 cursor-pointer {{ $notification->read_at ? '' : 'bg-blue-50/50 dark:bg-blue-900/20' }}">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                             {{--  Custom logic to display notification content based on type --}}
                            @if(isset($notification->data['message']))
                                {{ $notification->data['message'] }}
                            @else
                                Une nouvelle notification.
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <div class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        Aucune notification.
                    </div>
                @endforelse
            </div>
            
            @if($this->notifications->count() > 0)
                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 text-center">
                    <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400">Voir tout</a>
                </div>
            @endif
        </div>
    </div>
</div>
