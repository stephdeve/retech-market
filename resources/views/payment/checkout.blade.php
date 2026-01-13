<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            💳 {{ __('Paiement Sécurisé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Récapitulatif de la commande</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        Vous êtes sur le point de payer un total de <span class="font-bold text-blue-600 text-xl">{{ number_format($total, 2) }}
                                            @auth
                                                FCFA
                                            @endauth 
                                            @guest
                                                XOF
                                            @endguest
                                        </span> pour {{ count($cart) }} articles.
                    </p>

                    {{-- Kkiapay Widget Container --}}
                    <div class="flex justify-center">
                        <kkiapay-widget 
                            amount="{{ $total }}" 
                            key="{{ env('KKIAPAY_PUBLIC_KEY') }}" 
                            url="{{ asset('images/logo.png') }}" 
                            position="center" 
                            sandbox="{{ env('KKIAPAY_SANDBOX', 'true') }}"
                            callback="{{ route('payment.callback') }}"
                            theme="#2563EB">
                        </kkiapay-widget>
                    </div>

                    <div class="mt-8 text-sm text-gray-500">
                        <p>Paiement sécurisé par Kkiapay. Vos données sont chiffrées.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kkiapay SDK --}}
    <script src="https://cdn.kkiapay.me/k.js"></script>
</x-app-layout>
