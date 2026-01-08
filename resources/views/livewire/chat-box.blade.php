<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <div class="h-64 overflow-y-auto mb-4 border-b border-gray-200 dark:border-gray-700 pb-4" id="chat-container">
        @foreach($chatMessages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} mb-2">
                <div class="{{ $message->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-200' }} rounded-lg px-4 py-2 max-w-xs">
                    <p class="text-sm">{{ $message->content }}</p>
                    <span class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }} block text-right mt-1">
                        {{ $message->created_at->format('H:i') }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="flex gap-2">
        <input type="text" wire:model="newMessage" placeholder="Écrivez votre message..." class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
            Envoyer
        </button>
    </form>

    <script>
        // Auto-scroll to bottom
        const chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;

        window.addEventListener('livewire:updated', () => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
</div>
