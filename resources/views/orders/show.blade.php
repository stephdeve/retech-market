<x-app-layout>
    {{-- Header moderne --}}
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 border-b border-gray-200 dark:border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-3 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Commande #{{ $order->id }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Discussion et suivi de votre commande</p>
                    </div>
                </div>
                <div>
                    <span class="badge-success text-sm px-4 py-2">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Colonne gauche: Détails --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Card produit --}}
                    <div class="card p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Produit commandé
                        </h2>
                        <div class="flex items-start space-x-4">
                            @if($order->product->image_path)
                                <img class="h-24 w-24 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-700 shadow-md" src="{{ asset('storage/' . $order->product->image_path) }}" alt="{{ $order->product->name }}">
                            @else
                                <div class="h-24 w-24 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center text-4xl border-2 border-gray-200 dark:border-gray-700">📦</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                                    {{ $order->product->name }}
                                </h3>
                                <div class="flex items-baseline space-x-2 mb-3">
                                    <span class="text-3xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                        {{ number_format($order->total_price, 0) }}
                                    </span>
                                    <span class="text-xl font-bold text-gray-600 dark:text-gray-400">
                                        @auth
                                            {{ Auth::user()->currency }}
                                        @endauth 
                                        @guest
                                            XOF
                                        @endguest
                                    </span>
                                </div>
                                @if($order->product->category)
                                    <span class="badge-primary text-xs">{{ $order->product->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card participants --}}
                    <div class="card p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Participants
                        </h2>
                        <div class="space-y-4">
                            {{-- Vendeur --}}
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 border border-green-200/50 dark:border-green-800/50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                        {{ substr($order->product->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">Vendeur</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->product->user->name }}</p>
                                    </div>
                                </div>
                                @if(auth()->id() === $order->product->user->id)
                                    <span class="badge bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs">Vous</span>
                                @endif
                            </div>

                            {{-- Acheteur --}}
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 border border-blue-200/50 dark:border-blue-800/50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                        {{ substr($order->buyer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wide">Acheteur</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->buyer->name }}</p>
                                    </div>
                                </div>
                                @if(auth()->id() === $order->buyer->id)
                                    <span class="badge bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs">Vous</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card infos --}}
                    <div class="card p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informations
                        </h2>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Numéro de commande</span>
                                <span class="font-mono font-bold text-gray-900 dark:text-white">#{{ $order->id }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Date de création</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Heure</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">Statut</span>
                                    <span class="badge-success">{{ $order->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Colonne droite: Messagerie --}}
                <div class="lg:col-span-2">
                    <div class="card p-6 h-full flex flex-col">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Discussion
                            </h2>
                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                <span>En ligne</span>
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            @livewire('chat-box', ['order' => $order])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
