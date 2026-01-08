<x-app-layout>
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 border-b border-gray-200 dark:border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Mon Panier</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($cartItems) }} produit(s) dans votre panier</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(count($cartItems) > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Liste des produits --}}
                        <div class="lg:col-span-2 space-y-4">
                            @foreach($cartItems as $id => $details)
                                <div class="card p-6 hover:shadow-xl transition-all group">
                                    <div class="flex items-center space-x-4">
                                        {{-- Image --}}
                                        <div class="flex-shrink-0 h-20 w-20">
                                            @if($details['image'])
                                                <img class="h-20 w-20 rounded-xl object-cover border border-gray-200 dark:border-gray-700 group-hover:scale-105 transition-transform" src="{{ asset('storage/' . $details['image']) }}" alt="">
                                            @else
                                                <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center text-3xl border border-gray-200 dark:border-gray-700">📦</div>
                                            @endif
                                        </div>
                                        
                                        {{-- Détails --}}
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                                {{ $details['name'] }}
                                            </h3>
                                            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>{{ $details['seller'] }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <span class="text-2xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                                    {{ number_format($details['price'], 0) }} 
                                                    @auth
                                                        {{ Auth::user()->currency }}
                                                    @endauth 
                                                    @guest
                                                        XOF
                                                    @endguest
                                                </span>
                                            </div>
                                        </div>
                                        
                                        {{-- Actions --}}
                                        <div>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:text-white hover:bg-red-600 dark:text-red-400 dark:hover:bg-red-500 rounded-lg transition-all border border-red-600 dark:border-red-400" title="Supprimer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Résumé --}}
                        <div class="lg:col-span-1">
                            <div class="card p-6 sticky top-4">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Résumé de la commande</h2>
                                
                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                        <span>Sous-total ({{ count($cartItems) }} article{{ count($cartItems) > 1 ? 's' : '' }})</span>
                                        <span class="font-semibold">{{ number_format($total, 0) }} 
                                            @auth
                                                {{ Auth::user()->currency }}
                                            @endauth 
                                            @guest
                                                XOF
                                            @endguest

                                        </span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                        <span>Frais de service</span>
                                        <span class="font-semibold text-green-600 dark:text-green-400">Gratuit</span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                                            <span class="text-3xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                                {{ number_format($total, 0) }} 
                                                @auth
                                                    {{ Auth::user()->currency }}
                                                @endauth 
                                                @guest
                                                    XOF
                                                @endguest
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('payment.checkout') }}" class="btn-primary w-full py-4 text-base mb-3">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    Procéder au paiement
                                </a>
                                
                                <a href="{{ route('home') }}" class="btn-secondary w-full text-sm py-2.5">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Continuer mes achats
                                </a>
                                
                                {{-- Garanties --}}
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Paiement sécurisé
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Protection acheteur
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card p-16 text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Votre panier est vide</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            Découvrez nos meilleures offres tech et commencez à remplir votre panier !
                        </p>
                        <a href="{{ route('home') }}" class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Parcourir les produits
                        </a>
                    </div>
                @endif
        </div>
    </div>
</x-app-layout>
