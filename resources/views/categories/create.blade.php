<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer une catégorie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <x-label for="name" value="Nom de la catégorie" />
                            <x-input 
                                id="name" 
                                type="text" 
                                name="name" 
                                class="mt-1 block w-full" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                placeholder="Ex: Casques Audio"
                            />
                            <x-input-error for="name" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Le slug sera généré automatiquement à partir du nom.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Annuler
                            </a>
                            <x-button>
                                Créer la catégorie
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
