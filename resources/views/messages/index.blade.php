<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            💬 Messagerie
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Conversations</h3>

                    @if($threads->count() === 0)
                        <div class="text-center text-gray-500 dark:text-gray-400 py-12">Aucune conversation pour le moment.</div>
                    @else
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($threads as $key => $thread)
                                @php
                                    $product = $thread['product'] ?? null;
                                    $other = $thread['otherUser'] ?? null;
                                    $latest = $thread['latest'] ?? null;
                                    $unread = $thread['unreadCount'] ?? 0;
                                @endphp
                                <li>
                                    <a href="{{ $product && $other ? route('messages.show', ['product' => $product->id, 'user' => $other->id]) : '#' }}" class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                            @if($product?->image_path)
                                                <img src="{{ asset('storage/'.$product->image_path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                            @else
                                                <span class="text-xl">📦</span>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $product?->name ?? 'Conversation' }}
                                                </p>
                                                @if($unread > 0)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">{{ $unread }} non lu(s)</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                                Avec {{ $other?->name }}
                                            </p>
                                            @if($latest)
                                                <p class="text-sm text-gray-600 dark:text-gray-300 truncate mt-1">{{ $latest->content }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-3 text-xs text-gray-400">{{ optional($latest?->created_at)->diffForHumans() }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
