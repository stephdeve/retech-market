<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header + Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Welcome & Actions --}}
                <div class="md:col-span-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                            Bonjour, {{ Auth::user()->name }} 👋
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez vos annonces et suivez vos ventes.</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="btn-primary px-6 py-3 text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Créer une annonce
                    </a>
                </div>

                <!-- Stat Card 1 -->
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl p-6 rounded-2xl border border-white/20 dark:border-gray-700 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Annonces actives</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $products->where('status', 'En Vente')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2: Produits vendus -->
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl p-6 rounded-2xl border border-white/20 dark:border-gray-700 shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produits vendus</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $products->where('status', 'Vendu')->count() }}
                            </p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3: Valeur totale en vente (inventaire) -->
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl p-6 rounded-2xl border border-white/20 dark:border-gray-700 shadow-xl md:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Valeur totale (En vente)</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($products->where('status', 'En Vente')->sum('price'), 0, ',', ' ') }} <span class="text-lg font-normal text-gray-500">FcFA</span>
                            </p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl text-purple-600 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 4: Revenus totaux (ventes payées) -->
                {{-- Affiche le total des ventes payées et le nombre de ventes depuis le contrôleur --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl p-6 rounded-2xl border border-white/20 dark:border-gray-700 shadow-xl md:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenus totaux (Ventes)</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($salesTotal ?? 0, 0, ',', ' ') }} <span class="text-lg font-normal text-gray-500">FcFA</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ ($salesCount ?? 0) }} vente(s)</p>
                        </div>
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl text-amber-600 dark:text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                @if($statusData->count() > 0)
                    <div class="md:col-span-4 bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl p-6 rounded-2xl border border-white/20 dark:border-gray-700 shadow-xl">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Répartition de vos annonces</h3>
                        <div class="relative h-64 w-full flex justify-center">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Chart.js is imported in app.js --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('statusChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: {!! json_encode($statusLabels) !!},
                                datasets: [{
                                    data: {!! json_encode($statusData) !!},
                                    backgroundColor: [
                                        '#10B981', // Emerald 500 (En Vente assumed green)
                                        '#3B82F6', // Blue 500
                                        '#F59E0B', // Amber 500
                                        '#EF4444', // Red 500
                                        '#6B7280'  // Gray 500
                                    ],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#374151'
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>

            {{-- Message de succès --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 p-4 rounded-xl flex justify-between items-center shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700 dark:hover:text-green-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif

            {{-- Products Table --}}
            @if($products->count() > 0)
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Mes Annonces</h3>
                         <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs font-medium">{{ $products->count() }} produits</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50/50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produit</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Prix</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="relative px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-14 w-14 flex-shrink-0 relative">
                                                    @if($product->image_path)
                                                        <img class="h-14 w-14 rounded-xl object-cover shadow-sm border border-gray-200 dark:border-gray-700 group-hover:scale-105 transition-transform duration-300" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                                    @else
                                                        <div class="h-14 w-14 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-2xl shadow-sm border border-gray-200 dark:border-gray-700">📦</div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $product->category->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-bold text-brand-600 dark:text-brand-400 font-mono bg-brand-50 dark:bg-brand-900/20 px-2 py-1 rounded-lg">
                                                {{ number_format($product->price, 0, ',', ' ') }} FcFA
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->status == 'En Vente')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                    En ligne
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                    {{ $product->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col">
                                                <span>{{ $product->created_at->format('d M Y') }}</span>
                                                <span class="text-xs opacity-75">à {{ $product->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('products.show', $product) }}" class="p-2 text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition" title="Voir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" class="p-2 text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition" title="Éditer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <button onclick="confirmDelete({{ $product->id }})" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Supprimer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                            
                                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination (if applicable) --}}
                     @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl p-12 text-center border border-white/20 dark:border-gray-700">
                    <div class="mx-auto h-32 w-32 bg-gradient-to-br from-brand-100 to-blue-100 dark:from-brand-900/30 dark:to-blue-900/30 rounded-full flex items-center justify-center text-5xl mb-6 shadow-inner ring-8 ring-white/50 dark:ring-gray-800/50">
                        📦
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Aucune annonce pour le moment</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">Vous n'avez pas encore publié d'annonces. Commencez à vendre vos produits high-tech dès aujourd'hui !</p>
                    <a href="{{ route('products.create') }}" class="inline-flex items-center px-8 py-3 bg-brand-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-wider hover:bg-brand-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        ➕ Créer ma première annonce
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Script de confirmation de suppression --}}
    <script>
        function confirmDelete(productId) {
            if (confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')) {
                document.getElementById('delete-form-' + productId).submit();
            }
        }
    </script>
</x-app-layout>
