<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Currency -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="currency" value="{{ __('Devise préférée') }}" />
            <select id="currency" wire:model="state.currency" class="mt-1 block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-brand-500 dark:focus:border-brand-600 focus:ring-brand-500 dark:focus:ring-brand-600 rounded-md shadow-sm">
                <option value="XOF">Franc CFA (XOF)</option>
                <option value="EUR">Euro (€)</option>
                <option value="USD">Dollar ($)</option>
            </select>
            <x-input-error for="currency" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="phone" value="{{ __('Téléphone (optionnel)') }}" />
            <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="state.phone" autocomplete="tel" placeholder="ex: +229 97 12 34 56" />
            <x-input-error for="phone" class="mt-2" />
        </div>

        <!-- Vidéo de Présentation Vendeur -->
        <div class="col-span-6 sm:col-span-4" x-data="{ videoMode: '{{ $this->user->video_path ? 'upload' : ($this->user->video_url ? 'link' : 'upload') }}' }">
            <x-label value="🎥 Vidéo de Présentation (Style Story)" />
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 mb-3">
                Présentez-vous aux acheteurs avec une vidéo courte (max 2 min, style TikTok/Reels)
            </p>

            @if($this->user->video_path || $this->user->video_url)
                <div class="mb-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-purple-900 dark:text-purple-100 font-medium">
                            @if($this->user->video_path)
                                📹 Vidéo uploadée
                            @else
                                🔗 Lien vidéo actuel
                            @endif
                        </span>
                    </div>
                </div>
            @endif

            <div class="flex space-x-2 mb-3">
                <button type="button" @click="videoMode = 'upload'" :class="videoMode === 'upload' ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium transition">
                    📤 Fichier
                </button>
                <button type="button" @click="videoMode = 'link'" :class="videoMode === 'link' ? 'bg-purple-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium transition">
                    🔗 Lien
                </button>
            </div>

            <div x-show="videoMode === 'upload'" x-cloak>
                <input type="file" id="video" class="hidden" wire:model.live="video" accept="video/mp4,video/quicktime" />
                <div class="mt-1 flex justify-center px-6 pt-4 pb-4 border-2 border-purple-300 dark:border-purple-700 border-dashed rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/10 transition cursor-pointer" onclick="document.getElementById('video').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-10 w-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium text-purple-600 hover:text-purple-500">Cliquez pour uploader</span>
                        </div>
                        <p class="text-xs text-purple-500 dark:text-purple-400">MP4, MOV - Max 15 Mo / 2 min</p>
                    </div>
                </div>
                <x-input-error for="video" class="mt-2" />
            </div>

            <div x-show="videoMode === 'link'" x-cloak>
                <x-input id="video_url" type="url" class="mt-1 block w-full" wire:model="state.video_url" placeholder="https://youtube.com/watch?v=... ou https://tiktok.com/..." />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    ✅ YouTube, TikTok, Instagram Reels
                </p>
                <x-input-error for="video_url" class="mt-2" />
            </div>
        </div>

        <!-- Réseaux Sociaux -->
        <div class="col-span-6 sm:col-span-4 border-t border-gray-100 dark:border-gray-700 pt-4 mt-2">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Réseaux Sociaux / Liens Externes</h3>
            
            <div class="space-y-4">
                <!-- Website -->
                <div>
                    <x-label for="website" value="{{ __('Site Web') }}" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <x-input id="website" type="url" class="pl-10 block w-full" wire:model="state.social_links.website" placeholder="https://votre-site.com" />
                    </div>
                    <x-input-error for="social_links.website" class="mt-2" />
                </div>

                <!-- Facebook -->
                <div>
                    <x-label for="facebook" value="{{ __('Facebook') }}" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        <x-input id="facebook" type="url" class="pl-10 block w-full" wire:model="state.social_links.facebook" placeholder="https://facebook.com/votre-page" />
                    </div>
                    <x-input-error for="social_links.facebook" class="mt-2" />
                </div>

                <!-- Twitter / X -->
                <div>
                    <x-label for="twitter" value="{{ __('Twitter / X') }}" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-900 dark:text-gray-100" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </div>
                        <x-input id="twitter" type="url" class="pl-10 block w-full" wire:model="state.social_links.twitter" placeholder="https://twitter.com/votre-compte" />
                    </div>
                    <x-input-error for="social_links.twitter" class="mt-2" />
                </div>

                <!-- Instagram -->
                <div>
                    <x-label for="instagram" value="{{ __('Instagram') }}" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </div>
                        <x-input id="instagram" type="url" class="pl-10 block w-full" wire:model="state.social_links.instagram" placeholder="https://instagram.com/votre-profil" />
                    </div>
                    <x-input-error for="social_links.instagram" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
