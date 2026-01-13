<x-app-layout>
    <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/10 dark:to-pink-900/10 border-b border-gray-200 dark:border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="bg-gradient-to-br from-red-500 to-pink-600 p-3 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Mes Favoris</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $favorites->count() }} produit(s) sauvegardé(s)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($favorites->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($favorites as $product)
                        <div class="card-hover group">
                            {{-- Image --}}
                            <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-6xl">📦</div>
                                @endif
                                
                                {{-- Favori overlay --}}
                                <div class="absolute top-3 right-3">
                                    <livewire:toggle-favorite :product="$product" :key="'fav-'.$product->id" />
                                </div>
                                
                                {{-- Category badge --}}
                                <div class="absolute top-3 left-3">
                                    <span class="badge-primary backdrop-blur-sm">{{ $product->category->name }}</span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:bg-gradient-to-r group-hover:from-brand-600 group-hover:to-purple-600 group-hover:bg-clip-text group-hover:text-transparent transition-all">
                                    {{ $product->name }}
                                </h3>
                                
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-black bg-gradient-to-r from-brand-600 to-purple-600 bg-clip-text text-transparent">
                                        {{ number_format($product->price, 0) }}
                                        @auth
                                            FCFA
                                        @endauth 
                                        @guest
                                            XOF
                                        @endguest
                                    </span>
                                </div>

                                <a href="{{ route('products.show', $product) }}" class="btn-primary w-full text-sm py-2.5">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-16 text-center">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-red-100 to-pink-100 dark:from-red-900/20 dark:to-pink-900/20 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Aucun favori</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        Commencez à sauvegarder vos produits préférés en cliquant sur le cœur.
                    </p>
                    <a href="{{ route('home') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Découvrir les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
