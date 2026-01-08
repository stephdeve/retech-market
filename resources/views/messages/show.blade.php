<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💬 Conversation
            </h2>
            <a href="{{ route('messages.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400">← Retour</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ asset('storage/'.$product->image_path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                            @else
                                <span class="text-xl">📦</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Produit</div>
                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $product->name }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Avec</div>
                        <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $otherUser->name }}</div>
                    </div>
                </div>

                <div id="conversation" class="p-6 space-y-4 max-h-[60vh] overflow-y-auto bg-gray-50 dark:bg-gray-900/40">
                    @foreach($messages as $m)
                        @php $isMe = $m->sender_id === auth()->id(); @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] px-4 py-2 rounded-2xl text-sm {{ $isMe ? 'bg-brand-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-700' }}">
                                <div>{{ $m->content }}</div>
                                <div class="mt-1 text-[10px] {{ $isMe ? 'text-white/80' : 'text-gray-400' }}">{{ $m->created_at->format('d/m H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('messages.send', ['product' => $product->id, 'user' => $otherUser->id]) }}" class="p-4 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    @csrf
                    <input type="text" name="content" class="flex-1 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-brand-500 focus:ring-brand-500" placeholder="Écrire un message..." required maxlength="1000">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white rounded-xl hover:bg-brand-700 transition">Envoyer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Ecoute les nouveaux messages reçus en temps réel sur le canal privé de l'utilisateur
        document.addEventListener('DOMContentLoaded', () => {
            try {
                const userId = {{ auth()->id() }};
                const productId = {{ $product->id }};
                const otherId = {{ $otherUser->id }};
                const conv = document.getElementById('conversation');

                if (window.Echo) {
                    window.Echo.private('messages.' + userId)
                        .listen('.MessageSent', (e) => {
                            const msg = e?.message || {};
                            if (msg.product_id === productId && msg.sender_id === otherId) {
                                const wrap = document.createElement('div');
                                wrap.className = 'flex justify-start';
                                wrap.innerHTML = `<div class="max-w-[75%] px-4 py-2 rounded-2xl text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-700">`
                                    + `<div>${escapeHtml(msg.content)}</div>`
                                    + `<div class="mt-1 text-[10px] text-gray-400">${new Date(msg.created_at).toLocaleString()}</div>`
                                    + `</div>`;
                                conv.appendChild(wrap);
                                conv.scrollTop = conv.scrollHeight;
                            }
                        });
                }
            } catch (_) {}
        });

        function escapeHtml(text) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, (m) => map[m]);
        }
    </script>
</x-app-layout>
