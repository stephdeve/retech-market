<div>
    <button wire:click="toggle" 
            class="flex items-center justify-center p-3 rounded-full transition-all duration-300 focus:outline-none {{ $isFavorite ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-red-400' }}"
            title="{{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $isFavorite ? 'fill-current' : 'fill-none stroke-current' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</div>
