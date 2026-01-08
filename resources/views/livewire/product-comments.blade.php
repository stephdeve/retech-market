<div class="mt-12 border-t border-gray-100 dark:border-gray-700 pt-8">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
        💬 Commentaires ({{ $comments->count() }})
    </h3>

    {{-- Formulaire d'ajout --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @auth
        <form wire:submit.prevent="save" class="mb-8">
            <div class="mb-4">
                <textarea wire:model="content" 
                          class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" 
                          rows="3" 
                          placeholder="Posez une question ou laissez un commentaire..."></textarea>
                @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition disabled:opacity-50 flex items-center"
                        wire:loading.attr="disabled"
                        wire:target="save">
                    <span wire:loading.remove wire:target="save">Envoyer</span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Envoi...
                    </span>
                </button>
            </div>
        </form>
    @else
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg text-center mb-8">
            <p class="text-gray-600 dark:text-gray-400">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">Connectez-vous</a> pour laisser un commentaire.
            </p>
        </div>
    @endauth

    {{-- Liste des commentaires --}}
    <div class="space-y-6">
        @foreach($comments as $comment)
            <div class="flex space-x-4" wire:key="comment-{{ $comment->id }}">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 font-bold">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $comment->user->name }}</h4>
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 text-sm bg-gray-50 dark:bg-gray-800 p-3 rounded-lg rounded-tl-none">
                        {{ $comment->content }}
                    </p>
                    
                    @auth
                        @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                            <button wire:click="delete({{ $comment->id }})" 
                                    onclick="confirm('Supprimer ce commentaire ?') || event.stopImmediatePropagation()"
                                    class="text-xs text-red-500 hover:text-red-700 mt-1 font-medium">
                                Supprimer
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</div>
