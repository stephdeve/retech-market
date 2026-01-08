<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            📢 {{ __('Nouvelle annonce') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                
                {{-- Entête Formulaire --}}
                <div class="bg-blue-600 px-8 py-6">
                    <h3 class="text-white text-lg font-bold">Détails de votre produit</h3>
                    <p class="text-blue-100 text-sm mt-1">Remplissez les informations ci-dessous pour mettre votre produit en vente.</p>
                </div>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8" x-data="{ type: '{{ old('transaction_type', 'sale') }}' }">
                    @csrf

                    {{-- Catégorie --}}
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="category_id" name="category_id" required
                                    class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm appearance-none bg-none">
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom du produit --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Titre de l'annonce <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                               placeholder="Ex: iPhone 13 Pro Max - 128 Go - Parfait état"
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm placeholder-gray-400">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type de transaction --}}
                    <div>
                        <label for="transaction_type" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Type de transaction <span class="text-red-500">*</span>
                        </label>
                        <select id="transaction_type" name="transaction_type" x-model="type"
                                class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm">
                            <option value="sale">Vente</option>
                            <option value="trade">Troc</option>
                            <option value="both">Les deux (Vente & Troc)</option>
                        </select>
                        @error('transaction_type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Souhait de troc (affiché si troc ou les deux) --}}
                    <div x-show="type !== 'sale'" x-cloak>
                        <label for="trade_wishlist" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Contre quoi voulez-vous échanger ?
                        </label>
                        <input type="text" id="trade_wishlist" name="trade_wishlist" value="{{ old('trade_wishlist') }}" maxlength="255"
                               placeholder="Ex: iPhone 12, PS5, PC portable..."
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm placeholder-gray-400">
                        @error('trade_wishlist')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ville / Localisation --}}
                    <div>
                        <label for="city" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Ville / Localisation <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required maxlength="255"
                               placeholder="Ex: Cotonou - Haie Vive"
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm placeholder-gray-400">
                        @error('city')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Prix --}}
                    <div>
                        <label for="price" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Prix (€) <span class="text-red-500" x-show="type !== 'trade'">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 sm:text-sm">€</span>
                            </div>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" :required="type !== 'trade'" 
                                   min="0" max="9999999.99" step="0.01"
                                   placeholder="0.00"
                                   class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 pl-10 px-4 shadow-sm placeholder-gray-400">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" x-show="type === 'trade'">Le prix est optionnel pour une annonce en troc.</p>
                    </div>

                    {{-- Quantité --}}
                    <div>
                        <label for="quantity" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Quantité <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="0" max="999999" required
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm">
                        @error('quantity')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Description détaillée <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="6" required minlength="20"
                                  placeholder="Décrivez l'état, les caractéristiques techniques, les accessoires fournis..."
                                  class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 shadow-sm placeholder-gray-400">{{ old('description') }}</textarea>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-right">Minimum 20 caractères</p>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Photos du produit
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/50 transition cursor-pointer relative" id="drop-zone">
                            <div class="space-y-1 text-center" id="upload-prompt">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="image" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Télécharger une image</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, WEBP jusqu'à 2 Mo
                                </p>
                            </div>

                            {{-- Prévisualisation --}}
                            <div id="image-preview-container" class="hidden text-center w-full">
                                <img id="image-preview" src="#" alt="Prévisualisation" class="mx-auto h-64 object-contain rounded-lg shadow-md mb-4 bg-white">
                                <button type="button" id="remove-image" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                    ❌ Supprimer l'image
                                </button>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vidéo Showcase (Style TikTok) --}}
                    <div x-data="{ videoMode: 'upload' }">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                            🎥 Vidéo Showcase <span class="text-xs font-normal text-gray-500">(Optionnel - Style TikTok/Reels)</span>
                        </label>
                        
                        {{-- Tabs pour choisir Upload ou Lien --}}
                        <div class="flex space-x-2 mb-3">
                            <button type="button" @click="videoMode = 'upload'" :class="videoMode === 'upload' ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition">
                                📤 Uploader un fichier
                            </button>
                            <button type="button" @click="videoMode = 'link'" :class="videoMode === 'link' ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition">
                                🔗 Lien externe
                            </button>
                        </div>

                        {{-- Upload fichier vidéo --}}
                        <div x-show="videoMode === 'upload'" x-cloak>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-purple-300 dark:border-purple-700 border-dashed rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/10 transition cursor-pointer" id="video-drop-zone">
                                <div class="space-y-1 text-center" id="video-upload-prompt">
                                    <svg class="mx-auto h-12 w-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                        <label for="video" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                            <span>Télécharger une vidéo</span>
                                            <input id="video" name="video" type="file" class="sr-only" accept="video/mp4,video/quicktime" onchange="handleVideoUpload(this)">
                                        </label>
                                    </div>
                                    <p class="text-xs text-purple-500 dark:text-purple-400">
                                        MP4, MOV - Max 15 Mo / 2 minutes
                                    </p>
                                </div>
                                <div id="video-preview-container" class="hidden text-center w-full">
                                    <video id="video-preview" class="mx-auto h-64 object-contain rounded-lg shadow-md mb-4 bg-black" controls></video>
                                    <p id="video-info" class="text-sm text-gray-600 dark:text-gray-400 mb-2"></p>
                                    <button type="button" id="remove-video" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-red-700 bg-red-100 hover:bg-red-200 transition">
                                        ❌ Supprimer la vidéo
                                    </button>
                                </div>
                            </div>
                            @error('video')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lien externe --}}
                        <div x-show="videoMode === 'link'" x-cloak>
                            <input type="text" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/... ou https://tiktok.com/..." class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 py-3 px-4 shadow-sm placeholder-gray-400">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                ✅ Supports: YouTube, TikTok, Instagram Reels
                            </p>
                            @error('video_url')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 font-medium hover:text-gray-900 dark:hover:text-white transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                            ✅ Publier l'annonce
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script de prévisualisation (amélioré avec gestion d'état) --}}
    <script>
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('image-preview-container');
        const uploadPrompt = document.getElementById('upload-prompt');
        const removeButton = document.getElementById('remove-image');
        const dropZone = document.getElementById('drop-zone');

        function showPreview(src) {
            preview.src = src;
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
            dropZone.classList.remove('border-gray-300', 'hover:bg-gray-50');
        }

        function resetPreview() {
            imageInput.value = '';
            preview.src = '#';
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            dropZone.classList.add('border-gray-300', 'hover:bg-gray-50');
        }

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('❌ Veuillez sélectionner un fichier image valide.');
                    resetPreview();
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    showPreview(e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function(e) {
            e.preventDefault(); // Empêche le clic de propager au label
            e.stopPropagation();
            resetPreview();
        });

        // ===== Gestion Vidéo =====
        function handleVideoUpload(input) {
            const file = input.files[0];
            if (!file) return;

            // Vérification du type
            if (!file.type.startsWith('video/')) {
                alert('❌ Veuillez sélectionner un fichier vidéo valide (MP4 ou MOV).');
                input.value = '';
                return;
            }

            // Vérification de la taille (15 Mo max)
            const maxSize = 15 * 1024 * 1024; // 15 Mo en bytes
            if (file.size > maxSize) {
                alert('❌ La vidéo dépasse 15 Mo. Veuillez compresser votre fichier.');
                input.value = '';
                return;
            }

            // Créer un élément vidéo temporaire pour vérifier la durée
            const videoEl = document.createElement('video');
            videoEl.preload = 'metadata';
            
            videoEl.onloadedmetadata = function() {
                window.URL.revokeObjectURL(videoEl.src);
                const duration = videoEl.duration; // en secondes
                
                // Vérification de la durée (2 minutes = 120 secondes max)
                if (duration > 120) {
                    alert('❌ La vidéo dépasse 2 minutes (' + Math.round(duration) + 's). Veuillez la raccourcir.');
                    input.value = '';
                    return;
                }

                // Tout est OK, afficher la prévisualisation
                showVideoPreview(file, duration);
            };

            videoEl.src = URL.createObjectURL(file);
        }

        function showVideoPreview(file, duration) {
            const preview = document.getElementById('video-preview');
            const previewContainer = document.getElementById('video-preview-container');
            const uploadPrompt = document.getElementById('video-upload-prompt');
            const videoInfo = document.getElementById('video-info');
            const dropZone = document.getElementById('video-drop-zone');

            preview.src = URL.createObjectURL(file);
            videoInfo.textContent = `📹 ${file.name} - ${(file.size / (1024 * 1024)).toFixed(2)} Mo - ${Math.round(duration)}s`;
            
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
            dropZone.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/10');
            dropZone.classList.remove('border-purple-300', 'hover:bg-purple-50');
        }

        function resetVideoPreview() {
            const videoInput = document.getElementById('video');
            const preview = document.getElementById('video-preview');
            const previewContainer = document.getElementById('video-preview-container');
            const uploadPrompt = document.getElementById('video-upload-prompt');
            const dropZone = document.getElementById('video-drop-zone');

            videoInput.value = '';
            preview.src = '';
            
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            dropZone.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/10');
            dropZone.classList.add('border-purple-300', 'hover:bg-purple-50');
        }

        const removeVideoButton = document.getElementById('remove-video');
        if (removeVideoButton) {
            removeVideoButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                resetVideoPreview();
            });
        }
    </script>
</x-app-layout>
