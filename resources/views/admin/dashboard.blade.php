<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🛡️ {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bienvenue dans l'espace d'administration</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Vous êtes connecté en tant qu'administrateur. Ici, vous pourrez gérer les utilisateurs, les produits et les commentaires.
                </p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Stat Card 1 -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                        <div class="text-blue-600 dark:text-blue-400 font-bold text-xl mb-1">Utilisateurs</div>
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ \App\Models\User::count() }}</div>
                    </div>
                    
                    <!-- Stat Card 2 -->
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800">
                        <div class="text-green-600 dark:text-green-400 font-bold text-xl mb-1">Produits</div>
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ \App\Models\Product::count() }}</div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-100 dark:border-purple-800">
                        <div class="text-purple-600 dark:text-purple-400 font-bold text-xl mb-1">Rôles</div>
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ \Spatie\Permission\Models\Role::count() }}</div>
                    </div>

                    <!-- Charts: Users & Products -->
                    <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- User Registration Chart -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Nouveaux Utilisateurs</h4>
                            <div class="relative h-64">
                                <canvas id="userChart"></canvas>
                            </div>
                        </div>

                        <!-- Product Creation Chart -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Mises en Vente</h4>
                            <div class="relative h-64">
                                <canvas id="productChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Chart.js is imported in app.js --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const commonOptions = {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#374151' },
                                        grid: { color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB' }
                                    },
                                    x: {
                                        ticks: { color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#374151' },
                                        grid: { color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB' }
                                    }
                                },
                                plugins: {
                                    legend: { labels: { color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#374151' } }
                                }
                            };

                            // User Chart
                            new Chart(document.getElementById('userChart'), {
                                type: 'line',
                                data: {
                                    labels: {!! json_encode($labels) !!},
                                    datasets: [{
                                        label: 'Inscriptions',
                                        data: {!! json_encode($userCounts) !!},
                                        borderColor: '#3B82F6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: commonOptions
                            });

                            // Product Chart
                            new Chart(document.getElementById('productChart'), {
                                type: 'bar',
                                data: {
                                    labels: {!! json_encode($labels) !!},
                                    datasets: [{
                                        label: 'Produits ajoutés',
                                        data: {!! json_encode($productCounts) !!},
                                        backgroundColor: '#10B981',
                                        borderRadius: 4
                                    }]
                                },
                                options: commonOptions
                            });
                        });
                    </script>

                    {{-- Users List --}}
                    <div class="col-span-1 md:col-span-3 mt-8">
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Gestion des Utilisateurs</h4>
                        
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rôle</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date d'inscription</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($allUsers as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-200">{{ $user->email }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($user->hasRole('admin'))
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                            Admin
                                                        </span>
                                                    @elseif($user->hasRole('seller'))
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            Vendeur
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                            Utilisateur
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $user->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">
                                                        @if(!$user->hasRole('admin'))
                                                            <form action="{{ route('admin.users.promote', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Promouvoir cet utilisateur en administrateur ?');">
                                                                @csrf
                                                                <button type="submit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Promouvoir Admin">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                                                                </button>
                                                            </form>
                                                        @elseif(auth()->id() !== $user->id)
                                                            {{-- Only allow demoting other admins --}}
                                                            <form action="{{ route('admin.users.demote', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Rétrograder cet administrateur ?');">
                                                                @csrf
                                                                <button type="submit" class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300" title="Rétrograder">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if(auth()->id() !== $user->id)
                                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Supprimer">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
