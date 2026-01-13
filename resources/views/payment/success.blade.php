<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✅ Paiement réussi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden p-8">
                {{-- Récapitulatif paiement --}}
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Merci pour votre achat !</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Montant total réglé :
                        <span class="font-bold text-brand-600 text-lg">{{ number_format($total ?? $orders->sum('total_price'), 2) }} €</span>
                    </p>
                </div>

                {{-- Liste des articles --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendeur</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prix</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $order->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $order->product->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white font-mono">{{ number_format($order->total_price, 2) }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">Total</td>
                                <td class="px-6 py-4 text-right font-bold text-brand-600 dark:text-brand-400 text-lg">
                                    {{ number_format($total ?? $orders->sum('total_price'), 2) }}
                                    @auth
                                        FCFA
                                    @endauth 
                                    @guest
                                        XOF
                                    @endguest
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('purchases') }}" class="inline-flex items-center px-6 py-3 bg-brand-600 text-white rounded-md hover:bg-brand-700 transition">Voir mes achats</a>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition">Continuer mes achats</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
