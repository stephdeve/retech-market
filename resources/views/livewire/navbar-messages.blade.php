<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg transition-colors duration-200">
        <!-- Envelope Icon -->
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.903 1.46 5.498 3.746 7.143V22l3.43-1.882c.883.244 1.816.377 2.824.377 5.523 0 10-4.145 10-9.258C22 6.145 17.523 2 12 2zm1.09 13.04l-2.583-2.756-5.038 2.756 5.542-5.887 2.645 2.756 4.976-2.756-5.542 5.887z"/>
        </svg>

        <!-- Message Badge -->
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
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Messages</span>
            </div>

            <div class="max-h-64 overflow-y-auto">
                @forelse($this->messages as $message)
                    <a href="{{ $message->product ? route('messages.show', ['product' => $message->product->id, 'user' => $message->sender_id]) : route('messages.index') }}" class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 {{ $message->is_read ? '' : 'bg-blue-50/50 dark:bg-blue-900/20' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $message->sender->profile_photo_url }}" alt="{{ $message->sender->name }}">
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $message->sender->name }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">
                                    {{ $message->content }}
                                </p>
                                <p class="mt-1 text-xs text-brand-500 dark:text-brand-400">
                                    {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        Aucun nouveau message.
                    </div>
                @endforelse
            </div>
            
            <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 text-center">
                <a href="{{ route('messages.index') }}" class="text-xs font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400">Voir la messagerie</a>
            </div>
        </div>
    </div>
</div>
