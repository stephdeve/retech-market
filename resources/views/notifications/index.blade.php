<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-brand-500 to-purple-600 p-3 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100">Notifications</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $unreadCount }} non lue(s)</p>
                </div>
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Tout marquer comme lu</span>
                        </span>
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filtres -->
            <div class="mb-6 flex items-center space-x-3">
                <a href="{{ route('notifications.index') }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ !request('filter') ? 'bg-brand-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    Toutes ({{ auth()->user()->notifications()->count() }})
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ request('filter') === 'unread' ? 'bg-brand-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    Non lues ({{ $unreadCount }})
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ request('filter') === 'read' ? 'bg-brand-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    Lues
                </a>
            </div>

            <!-- Notifications List -->
            <div class="space-y-3">
                @forelse($notifications as $n)
                    @php
                        $type = $n->type ?? '';
                        $iconClass = 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300';
                        $icon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                        
                        if (str_contains($type, 'ProductSold')) {
                            $iconClass = 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400';
                            $icon = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                        } elseif (str_contains($type, 'OrderPlaced')) {
                            $iconClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400';
                            $icon = 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z';
                        } elseif (str_contains($type, 'NewMessage')) {
                            $iconClass = 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400';
                            $icon = 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z';
                        } elseif (str_contains($type, 'LowStock') || str_contains($type, 'OutOfStock')) {
                            $iconClass = 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400';
                            $icon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
                        }
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border {{ $n->read_at ? 'border-gray-200 dark:border-gray-700' : 'border-brand-200 dark:border-brand-700 ring-2 ring-brand-100 dark:ring-brand-900/30' }} hover:shadow-md transition-all duration-200 overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="{{ $iconClass }} p-3 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                @if(isset($n->data['message']))
                                                    {{ $n->data['message'] }}
                                                @else
                                                    Nouvelle notification
                                                @endif
                                            </p>
                                            
                                            @if(isset($n->data['product_name']))
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <span class="font-medium">Produit:</span> {{ $n->data['product_name'] }}
                                                </p>
                                            @endif
                                            
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $n->created_at->diffForHumans() }}
                                                </span>
                                                @if(!$n->read_at)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-100 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300">
                                                        Nouveau
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$n->read_at)
                                                <form method="POST" action="{{ route('notifications.mark-read', $n->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" title="Marquer comme lue" class="p-1.5 text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('notifications.delete', $n->id) }}" class="inline" onsubmit="return confirm('Supprimer cette notification ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Supprimer" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-16">
                        <div class="text-center">
                            <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Aucune notification</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Vous êtes à jour !</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(method_exists($notifications, 'links') && $notifications->hasPages())
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
