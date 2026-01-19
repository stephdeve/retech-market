<x-app-layout>
    <div x-data="{ 
        videoModalOpen: false,
        sellerVideoModalOpen: false
    }">
    {{-- Breadcrumb moderne --}}
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-600 dark:text-gray-400 dark:hover:text-brand-400 transition-colors">
                    Accueil
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-brand-600 dark:text-gray-400 dark:hover:text-brand-400 transition-colors">
                    {{ $product->category->name }}
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">{{ Str::limit($product->name, 40) }}</span>
            </nav>
        </div>
    </div>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Colonne gauche: Image + Info rapides --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Image principale --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200/50 dark:border-gray-700/50">
                        <div class="relative aspect-video bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center group">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-700 ease-out">
                            @else
                                <div class="text-center">
                                    <div class="text-gray-300 dark:text-gray-600 text-9xl mb-4"></div>
                                    <p class="text-gray-400 text-sm uppercase tracking-wide font-semibold">Aucune image disponible</p>
                                </div>
                            @endif
                            
                            {{-- Bouton Vidéo Showcase si disponible --}}
                            @if($product->video_path || $product->video_url)
                                <button @click="videoModalOpen = true" 
                                        class="absolute bottom-4 left-1/2 -translate-x-1/2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full font-bold shadow-2xl hover:shadow-purple-500/50 hover:scale-105 transition-all flex items-center space-x-2 backdrop-blur-sm border border-white/20 animate-pulse hover:animate-none">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                                    </svg>
                                    <span>Voir en vidéo</span>
                                </button>
                            @endif
                            
                            {{-- Badge stock en overlay --}}
                            @if(isset($product->quantity))
                                <div class="absolute top-4 right-4">
                                    @if($product->quantity === 0)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-gray-900/80 dark:bg-gray-100/80 text-white dark:text-gray-900 backdrop-blur-sm border border-white/20">
                                             Épuisé
                                        </span>
                                    @elseif($product->quantity === 1)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-orange-500/90 text-white backdrop-blur-sm border border-white/20 animate-pulse">
                                            
                                            Dernier exemplaire
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-green-500/90 text-white backdrop-blur-sm border border-white/20">
                                            ✓ {{ $product->quantity }} en stock
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            {{-- Favori en overlay --}}
                            <div class="absolute top-4 left-4">
                                <livewire:toggle-favorite :product="$product" />
                            </div>
                        </div>
                    </div>
                    
                    {{-- Description détaillée --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200/50 dark:border-gray-700/50">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Description
                        </h2>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 leading-relaxed">
                            <p class="whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    </div>
                    
                    {{-- Souhait de troc --}}
                    @if(($product->transaction_type === 'trade' || $product->transaction_type === 'both') && !empty($product->trade_wishlist))
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-2xl shadow-lg p-6 border border-blue-200/50 dark:border-blue-800/50">
                            <h3 class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Souhait de troc
                            </h3>
                            <p class="text-blue-800 dark:text-blue-200">{{ $product->trade_wishlist }}</p>
                        </div>
                    @endif
                </div>

                {{-- Colonne droite: Actions et infos --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Card principale --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200/50 dark:border-gray-700/50 sticky top-4">
                        {{-- Titre --}}
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-brand-100 to-purple-100 dark:from-brand-900/30 dark:to-purple-900/30 text-brand-700 dark:text-brand-300 border border-brand-200 dark:border-brand-800 mb-3">
                                {{ $product->category->name }}
                            </span>
                            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white leading-tight mb-2">
                                {{ $product->name }}
                            </h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Publié {{ $product->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Prix --}}
                        <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-baseline space-x-2 mb-3">
                                <span class="text-4xl font-black bg-gradient-to-r from-brand-600 via-purple-600 to-pink-600 dark:from-brand-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">
                                    {{ number_format($product->price, 0) }}
                                </span>
                                <span class="text-2xl font-bold text-gray-600 dark:text-gray-400">
                                    @auth
                                        FCFA
                                    @endauth 
                                    @guest
                                        XOF
                                    @endguest
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if(in_array($product->transaction_type ?? 'sale', ['sale','both']))
                                    <span class="badge-success"> Vente</span>
                                @endif
                                @if(in_array($product->transaction_type ?? 'sale', ['trade','both']))
                                    <span class="badge-primary"> Troc accepté</span>
                                @endif
                                @if(!empty($product->city))
                                    <span class="badge bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">📍 {{ $product->city }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Vendeur --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-xs uppercase font-bold text-gray-500 dark:text-gray-400 tracking-wider mb-3">Vendu par</h3>
                            <div class="flex items-center space-x-3">
                                {{-- Avatar avec Story style si vidéo vendeur disponible --}}
                                @if($product->user->video_path || $product->user->video_url)
                                    <button @click="sellerVideoModalOpen = true" class="relative h-12 w-12 rounded-xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg ring-2 ring-purple-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-800 hover:scale-110 transition-transform cursor-pointer">
                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                                        </span>
                                        {{ substr($product->user->name, 0, 1) }}
                                    </button>
                                @else
                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                        {{ substr($product->user->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $product->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-0.5">
                                        <svg class="w-3.5 h-3.5 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Réponse rapide
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div>
                        @auth
                            @if(auth()->id() === $product->user_id)
                                <div class="space-y-3">
                                    <a href="{{ route('products.edit', $product) }}" class="btn-secondary w-full">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifier l'annonce
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm(' Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger w-full">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @else
                                @php $out = (isset($product->quantity) && $product->quantity === 0) || ($product->is_available === false); @endphp
                                
                                @if(!$out)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn-primary w-full py-4 text-base">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Ajouter au panier
                                        </button>
                                    </form>
                                @else
                                    <div class="mb-3 flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Produit indisponible
                                    </div>
                                @endif

                                @if($out)
                                    <form method="POST" action="{{ route('products.restock-subscribe', $product) }}" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn-secondary w-full">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                            Notifier retour en stock
                                        </button>
                                    </form>
                                @endif
                                
                                {{-- Message rapide --}}
                                <div class="mb-3">
                                    <livewire:product-quick-message :product="$product" />
                                </div>

                                {{-- Contact rapide --}}
                                @if(!$out && !empty($product->user->phone))
                                <div x-data="{ revealed: false, full: '', init(){ const raw = '{{ preg_replace('/[^\d+]/', '', $product->user->phone) }}'; this.full = raw; }}" class="space-y-3">
                                    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-mono text-gray-800 dark:text-gray-200">
                                                <span x-show="!revealed">{{ $product->user->masked_phone ?? 'Numéro indisponible' }}</span>
                                                <span x-show="revealed" x-cloak x-text="full"></span>
                                            </div>
                                            <button type="button" @click="revealed = true" x-show="!revealed" class="btn-secondary px-3 py-1.5 text-xs">
                                                Révéler
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2" x-show="revealed" x-cloak>
                                        <a :href="'tel:' + full" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all hover:-translate-y-0.5 hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            Appeler
                                        </a>
                                        <a :href="'https://wa.me/' + full.replace(/[^\d]/g, '') + '?text=' + encodeURIComponent('Bonjour, je suis intéressé par votre annonce: {{ $product->name }}')" class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-all hover:-translate-y-0.5 hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            WhatsApp
                                        </a>
                                    </div>
                                </div>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full py-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Connectez-vous pour acheter
                            </a>
                        @endauth
                    </div>
                </div>
                
                {{-- Garanties et sécurité --}}
                <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/10 dark:to-blue-900/10 rounded-2xl p-4 border border-green-200/50 dark:border-green-800/50">
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div>
                            <div class="text-2xl mb-1">🔒</div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Paiement sécurisé</p>
                        </div>
                        <div>
                            <div class="text-2xl mb-1">✓</div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Protection acheteur</p>
                        </div>
                        <div>
                            <div class="text-2xl mb-1">💬</div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Support rapide</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Commentaires en bas --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200/50 dark:border-gray-700/50">
                <livewire:product-comments :product="$product" />
            </div>
        </div>
    </div>

    {{-- Modal Vidéo Produit --}}
    @if($product->video_path || $product->video_url)
        <div @keydown.escape.window="videoModalOpen = false"
             x-show="videoModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            {{-- Overlay --}}
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="videoModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="videoModalOpen = false"
                     class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-90 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                {{-- Modal Content --}}
                <div x-show="videoModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     @click.stop>
                    
                    {{-- Close Button --}}
                    <button @click="videoModalOpen = false" class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    {{-- Video Player Component --}}
                    <x-video-player 
                        :videoPath="$product->video_path" 
                        :videoUrl="$product->video_url"
                        :title="$product->name"
                        class="max-w-full mx-auto"
                    />
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Vidéo Vendeur --}}
    @if($product->user->video_path || $product->user->video_url)
        <div @keydown.escape.window="sellerVideoModalOpen = false"
             x-show="sellerVideoModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            {{-- Overlay --}}
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="sellerVideoModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="sellerVideoModalOpen = false"
                     class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-90 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                {{-- Modal Content --}}
                <div x-show="sellerVideoModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     @click.stop>
                    
                    {{-- Header --}}
                    <div class="absolute top-0 left-0 right-0 z-10 p-4 bg-gradient-to-b from-black/60 to-transparent">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ substr($product->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $product->user->name }}</p>
                                    <p class="text-white/80 text-xs">Présentation du vendeur</p>
                                </div>
                            </div>
                            <button @click="sellerVideoModalOpen = false" class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Video Player Component --}}
                    <x-video-player 
                        :videoPath="$product->user->video_path" 
                        :videoUrl="$product->user->video_url"
                        :title="'Présentation de ' . $product->user->name"
                        class="max-w-full mx-auto"
                    />
                </div>
            </div>
        </div>
    @endif
    </div>
</x-app-layout>
