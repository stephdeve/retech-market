<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-edit"></i>
             {{ __('Modifier l\'annonce') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                
                <div class="bg-yellow-600 px-8 py-6">
                    <h3 class="text-white text-lg font-bold">Mise à jour du produit</h3>
                    <p class="text-yellow-100 text-sm mt-1">Modifiez les informations ci-dessous pour mettre à jour votre annonce.</p>
                </div>

                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8" x-data="{ type: '{{ old('transaction_type', $product->transaction_type ?? 'sale') }}' }">
                    @csrf
                    @method('PUT')

                    {{-- Catégorie --}}
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="category_id" name="category_id" required
                                    class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm appearance-none bg-none">
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom du produit --}}
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Titre de l'annonce <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="255"
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Type de transaction --}}
                    <div>
                        <label for="transaction_type" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Type de transaction <span class="text-red-500">*</span>
                        </label>
                        <select id="transaction_type" name="transaction_type" x-model="type"
                                class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm">
                            <option value="sale" {{ old('transaction_type', $product->transaction_type) === 'sale' ? 'selected' : '' }}>Vente</option>
                            <option value="trade" {{ old('transaction_type', $product->transaction_type) === 'trade' ? 'selected' : '' }}>Troc</option>
                            <option value="both" {{ old('transaction_type', $product->transaction_type) === 'both' ? 'selected' : '' }}>Les deux (Vente & Troc)</option>
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
                        <input type="text" id="trade_wishlist" name="trade_wishlist" value="{{ old('trade_wishlist', $product->trade_wishlist) }}" maxlength="255"
                               placeholder="Ex: iPhone 12, PS5, PC portable..."
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm placeholder-gray-400">
                        @error('trade_wishlist')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ville / Localisation --}}
                    <div>
                        <label for="city" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Ville / Localisation <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city', $product->city) }}" required maxlength="255"
                               placeholder="Ex: Cotonou - Haie Vive"
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm placeholder-gray-400">
                        @error('city')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Prix --}}
                    <div x-show="type !== 'trade'" x-cloak>
                        <label for="price" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Prix (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 sm:text-sm">FCFA</span>
                            </div>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" :required="type !== 'trade'" 
                                   min="0" max="9999999.99" step="0.01"
                                   class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 pl-16 px-4 shadow-sm">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Quantité --}}
                    <div>
                        <label for="quantity" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Quantité <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" min="0" max="999999" required
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm">
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
                                  class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-yellow-500 focus:ring-yellow-500 py-3 px-4 shadow-sm">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Photos du produit
                        </label>
                        
                        {{-- Image actuelle (si existe) --}}
                        @if($product->image_path)
                            <div class="mb-4 flex items-center p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="Actuelle" class="h-16 w-16 rounded-lg object-cover mr-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Image actuelle</p>
                                    <p class="text-xs text-gray-500">Sera remplacée si vous en téléchargez une nouvelle.</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900/50 transition cursor-pointer relative" id="drop-zone">
                            <div class="space-y-1 text-center" id="upload-prompt">
                                <i class="fas fa-image mx-auto text-gray-400" style="font-size: 3rem;"></i>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="image" class="relative cursor-pointer rounded-md font-medium text-yellow-600 hover:text-yellow-500 focus-within:outline-none">
                                        <span>Changer l'image</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, WEBP jusqu'à 2 Mo
                                </p>
                            </div>

                            {{-- Prévisualisation (cachée par défaut) --}}
                            <div id="image-preview-container" class="hidden text-center w-full">
                                <img id="image-preview" src="#" alt="Prévisualisation" class="mx-auto h-64 object-contain rounded-lg shadow-md mb-4 bg-white">
                                <button type="button" id="remove-image" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                    <i class="fas fa-times mr-1"></i>Annuler le changement
                                </button>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vidéo Showcase (Style TikTok) --}}
                    <div x-data="{ videoMode: '{{ $product->video_path ? 'upload' : ($product->video_url ? 'link' : 'upload') }}' }">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-video mr-1"></i>
                             Vidéo Showcase <span class="text-xs font-normal text-gray-500">(Optionnel - Style TikTok/Reels)</span>
                        </label>
                        
                        {{-- Vidéo actuelle si existe --}}
                        @if($product->video_path || $product->video_url)
                            <div class="mb-4 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center">
                                    <i class="fas fa-video text-purple-600" style="font-size: 2rem;"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-purple-900 dark:text-purple-100">Vidéo actuelle</p>
                                        @if($product->video_path)
                                            <p class="text-xs text-purple-700 dark:text-purple-300">📹 Fichier uploadé</p>
                                        @else
                                            <p class="text-xs text-purple-700 dark:text-purple-300">🔗 {{ Str::limit($product->video_url, 50) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-purple-600 dark:text-purple-400 mt-2">Sera remplacée si vous en ajoutez une nouvelle.</p>
                            </div>
                        @endif
                        
                        {{-- Tabs pour choisir Upload ou Lien --}}
                        <div class="flex space-x-2 mb-3">
                            <button type="button" @click="videoMode = 'upload'" :class="videoMode === 'upload' ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition">
                                <i class="fas fa-upload mr-1"></i> Uploader un fichier
                            </button>
                            <button type="button" @click="videoMode = 'link'" :class="videoMode === 'link' ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition">
                                <i class="fas fa-link mr-1"></i> Lien externe
                            </button>
                        </div>

                        {{-- Upload fichier vidéo --}}
                        <div x-show="videoMode === 'upload'" x-cloak>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-purple-300 dark:border-purple-700 border-dashed rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/10 transition cursor-pointer" id="video-drop-zone-edit">
                                <div class="space-y-1 text-center" id="video-upload-prompt-edit">
                                    <i class="fas fa-video mx-auto text-purple-400" style="font-size: 3rem;"></i>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                        <label for="video-edit" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                            <span>Télécharger une vidéo</span>
                                            <input id="video-edit" name="video" type="file" class="sr-only" accept="video/mp4,video/quicktime" onchange="handleVideoUploadEdit(this)">
                                        </label>
                                    </div>
                                    <p class="text-xs text-purple-500 dark:text-purple-400">
                                        MP4, MOV - Max 15 Mo / 2 minutes
                                    </p>
                                </div>
                                <div id="video-preview-container-edit" class="hidden text-center w-full">
                                    <video id="video-preview-edit" class="mx-auto h-64 object-contain rounded-lg shadow-md mb-4 bg-black" controls></video>
                                    <p id="video-info-edit" class="text-sm text-gray-600 dark:text-gray-400 mb-2"></p>
                                    <button type="button" id="remove-video-edit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-red-700 bg-red-100 hover:bg-red-200 transition">
                                        <i class="fas fa-times mr-1"></i> Supprimer la vidéo
                                    </button>
                                </div>
                            </div>
                            @error('video')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lien externe --}}
                        <div x-show="videoMode === 'link'" x-cloak>
                            <input type="text" name="video_url" value="{{ old('video_url', $product->video_url) }}" placeholder="https://youtube.com/... ou https://tiktok.com/..." class="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 py-3 px-4 shadow-sm placeholder-gray-400">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-check mr-1"></i> Supports: YouTube, TikTok, Instagram Reels
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
                        <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script (identique à create) --}}
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
            dropZone.classList.add('border-yellow-500', 'bg-yellow-50');
            dropZone.classList.remove('border-gray-300', 'hover:bg-gray-50');
        }

        function resetPreview() {
            imageInput.value = '';
            preview.src = '#';
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            dropZone.classList.remove('border-yellow-500', 'bg-yellow-50');
            dropZone.classList.add('border-gray-300', 'hover:bg-gray-50');
        }

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert(' Veuillez sélectionner un fichier image valide.');
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
            e.preventDefault();
            e.stopPropagation();
            resetPreview();
        });

        // ===== Gestion Vidéo Edit =====
        function handleVideoUploadEdit(input) {
            const file = input.files[0];
            if (!file) return;

            if (!file.type.startsWith('video/')) {
                alert('Veuillez sélectionner un fichier vidéo valide (MP4 ou MOV).');
                input.value = '';
                return;
            }

            const maxSize = 15 * 1024 * 1024;
            if (file.size > maxSize) {
                alert(' La vidéo dépasse 15 Mo. Veuillez compresser votre fichier.');
                input.value = '';
                return;
            }

            const videoEl = document.createElement('video');
            videoEl.preload = 'metadata';
            
            videoEl.onloadedmetadata = function() {
                window.URL.revokeObjectURL(videoEl.src);
                const duration = videoEl.duration;
                
                if (duration > 120) {
                    alert('La vidéo dépasse 2 minutes (' + Math.round(duration) + 's). Veuillez la raccourcir.');
                    input.value = '';
                    return;
                }

                showVideoPreviewEdit(file, duration);
            };

            videoEl.src = URL.createObjectURL(file);
        }

        function showVideoPreviewEdit(file, duration) {
            const preview = document.getElementById('video-preview-edit');
            const previewContainer = document.getElementById('video-preview-container-edit');
            const uploadPrompt = document.getElementById('video-upload-prompt-edit');
            const videoInfo = document.getElementById('video-info-edit');
            const dropZone = document.getElementById('video-drop-zone-edit');

            preview.src = URL.createObjectURL(file);
            videoInfo.textContent = `📹 ${file.name} - ${(file.size / (1024 * 1024)).toFixed(2)} Mo - ${Math.round(duration)}s`;
            
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
            dropZone.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/10');
            dropZone.classList.remove('border-purple-300', 'hover:bg-purple-50');
        }

        function resetVideoPreviewEdit() {
            const videoInput = document.getElementById('video-edit');
            const preview = document.getElementById('video-preview-edit');
            const previewContainer = document.getElementById('video-preview-container-edit');
            const uploadPrompt = document.getElementById('video-upload-prompt-edit');
            const dropZone = document.getElementById('video-drop-zone-edit');

            videoInput.value = '';
            preview.src = '';
            
            previewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            dropZone.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/10');
            dropZone.classList.add('border-purple-300', 'hover:bg-purple-50');
        }

        const removeVideoButtonEdit = document.getElementById('remove-video-edit');
        if (removeVideoButtonEdit) {
            removeVideoButtonEdit.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                resetVideoPreviewEdit();
            });
        }
    </script>
</x-app-layout>
