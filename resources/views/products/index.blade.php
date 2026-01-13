<x-app-layout>
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-br from-white via-brand-50/30 to-purple-50/30 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 border-b border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
        {{-- Decorative background elements --}}
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-brand-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                    <span class="block">La technologie de seconde main</span>
                    <span class="block mt-2 bg-gradient-to-r from-brand-600 via-purple-600 to-pink-600 dark:from-brand-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent">au meilleur prix</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Achetez et vendez vos smartphones, ordinateurs et accessoires tech en toute confiance sur ReTech Market.
                </p>
                
                {{-- Barre de recherche et filtre intégrés --}}
                <div class="mt-10 max-w-3xl mx-auto">
                    <form method="GET" action="{{ route('home') }}" class="sm:flex shadow-2xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <div class="min-w-0 flex-1 relative">
                            <label for="search" class="sr-only">Rechercher</label>
                            <input type="text" name="search" id="search" 
                                   class="block w-full pl-6 pr-3 py-5 text-base border-none focus:ring-2 focus:ring-brand-500 focus:outline-none dark:bg-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all" 
                                   placeholder="Que recherchez-vous ? (ex: iPhone 12, MacBook...)"
                                   value="{{ request('search') }}">
                        </div>
                        <div class="relative bg-white dark:bg-gray-900 border-l border-gray-100 dark:border-gray-800">
                            <select name="category" class="h-full pl-4 pr-10 py-4 bg-transparent border-none text-gray-500 focus:ring-0 focus:outline-none dark:text-gray-300 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-8 py-5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 text-white font-bold text-lg transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Trouver
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Entête de section --}}
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Dernières annonces</h2>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Les pépites tech ajoutées récemment par notre communauté.</p>
                </div>
                @auth
                    <a href="{{ route('products.create') }}" class="hidden sm:inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Vendre un produit
                    </a>
                @endauth
            </div>

            {{-- Grille de produits --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-200/50 dark:border-gray-700/50 flex flex-col h-full transform hover:-translate-y-2 hover:scale-[1.02]">
                            
                            {{-- Image avec badge catégorie --}}
                            <div class="relative w-full h-56 bg-gray-200 dark:bg-gray-700 rounded-t-2xl overflow-hidden group-hover:opacity-100 transition-opacity">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover object-center transform group-hover:scale-110 transition-transform duration-700 ease-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl">📦</div>
                                @endif
                                
                                {{-- Badge catégorie flottant --}}
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/90 dark:bg-gray-900/90 text-gray-800 dark:text-gray-200 backdrop-blur-sm shadow-sm">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                {{-- Badges transaction & stock --}}
                                <div class="absolute top-4 right-4 space-x-2 text-right">
                                    @if(in_array($product->transaction_type ?? 'sale', ['sale','both']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700 border border-green-200">🟢 Vente</span>
                                    @endif
                                    @if(in_array($product->transaction_type ?? 'sale', ['trade','both']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 border border-blue-200">🔄 Troc accepté</span>
                                    @endif
                                    @if(isset($product->quantity) && $product->quantity === 1)
                                        <div><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-orange-100 text-orange-800 border border-orange-200">⚠️ Dernier exemplaire !</span></div>
                                    @endif
                                    @if(isset($product->quantity) && $product->quantity === 0)
                                        <div><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-200 text-gray-800 border border-gray-300">❌ Épuisé</span></div>
                                    @endif
                                </div>
                                @if(isset($product->quantity) && $product->quantity === 0)
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-white/90 text-gray-900">❌ Épuisé</span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Contenu --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-1 mb-2 group-hover:bg-gradient-to-r group-hover:from-brand-600 group-hover:to-purple-600 group-hover:bg-clip-text group-hover:text-transparent transition-all duration-200">
                                    <a href="{{ route('products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                {{-- Localisation --}}
                                @if(!empty($product->city))
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">📍 {{ $product->city }}</p>
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
                                    {{ $product->description }}
                                </p>
                                
                                {{-- Bas de carte : Prix et User --}}
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-400">Prix</span>
                                        <span class="text-xl font-bold text-brand-600 dark:text-brand-400">
                                            {{ number_format($product->price, 0) }}
                                            @auth
                                                FCFA
                                            @endauth 
                                            @guest
                                                XOF
                                            @endguest
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center mr-2 text-xs font-bold text-brand-600 dark:text-brand-300">
                                            {{ substr($product->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Lien complet absolu pour l'accessibilité (désactivé si épuisé) --}}
                            @if(!isset($product->quantity) || $product->quantity > 0)
                                <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-10 focus:outline-none">
                                    <span class="sr-only">Voir les détails de {{ $product->name }}</span>
                                </a>
                            @else
                                <div class="absolute inset-0 z-10 cursor-not-allowed" aria-disabled="true"></div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Pagination moderne --}}
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                    <div class="mx-auto h-24 w-24 bg-brand-50 dark:bg-brand-900/30 rounded-full flex items-center justify-center text-4xl mb-6">
                        🔍
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aucun résultat trouvé</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Essayez d'ajuster vos critères de recherche ou supprimez les filtres.</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="text-brand-600 hover:text-brand-500 font-medium">
                            Tout effacer
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
