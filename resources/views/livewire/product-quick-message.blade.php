<div>
    @if(session('message_sent'))
        <div class="mb-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
            {{ session('message_sent') }}
        </div>
    @endif

    <form wire:submit.prevent="send" class="space-y-2">
        <label class="block text-xs font-semibold text-gray-500">Envoyer un premier message</label>
        <div class="flex items-center gap-2">
            <input type="text" wire:model.defer="newMessage" class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-brand-500 focus:ring-brand-500" placeholder="Bonjour, je suis intéressé...">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white rounded-lg hover:bg-brand-700 transition">
                Envoyer
            </button>
        </div>
        @error('newMessage')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </form>
</div>
