@props(['videoPath' => null, 'videoUrl' => null, 'title' => 'Vidéo'])

<div {{ $attributes->merge(['class' => 'video-player-container']) }}>
    @if($videoPath)
        {{-- Vidéo uploadée localement (HTML5) --}}
        <div class="relative w-full" style="aspect-ratio: 9/16; max-height: 600px;">
            <video 
                class="w-full h-full object-cover rounded-2xl shadow-2xl bg-black"
                controls
                preload="metadata"
                loading="lazy"
                playsinline
            >
                <source src="{{ asset('storage/' . $videoPath) }}" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
        </div>

    @elseif($videoUrl)
        {{-- Vidéo externe (YouTube, TikTok, Instagram) --}}
        @php
            $isYouTube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
            $isTikTok = str_contains($videoUrl, 'tiktok.com');
            $isInstagram = str_contains($videoUrl, 'instagram.com');
            
            // Extraction de l'ID YouTube
            if ($isYouTube) {
                if (str_contains($videoUrl, 'youtu.be/')) {
                    preg_match('/youtu\.be\/([^?]+)/', $videoUrl, $matches);
                    $youtubeId = $matches[1] ?? null;
                } else {
                    preg_match('/[?&]v=([^&]+)/', $videoUrl, $matches);
                    $youtubeId = $matches[1] ?? null;
                }
            }
        @endphp

        @if($isYouTube && isset($youtubeId))
            {{-- YouTube Embed --}}
            <div class="relative w-full" style="aspect-ratio: 9/16; max-height: 600px;">
                <iframe 
                    class="w-full h-full rounded-2xl shadow-2xl"
                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0"
                    title="{{ $title }}"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
            </div>

        @elseif($isTikTok)
            {{-- TikTok Embed --}}
            <div class="relative w-full" style="aspect-ratio: 9/16; max-height: 600px;">
                <blockquote 
                    class="tiktok-embed w-full h-full rounded-2xl shadow-2xl overflow-hidden" 
                    cite="{{ $videoUrl }}" 
                    data-video-id="{{ last(explode('/', parse_url($videoUrl, PHP_URL_PATH))) }}"
                    style="max-width: 605px; min-width: 325px;"
                >
                    <section>
                        <a target="_blank" title="{{ $title }}" href="{{ $videoUrl }}">Voir sur TikTok</a>
                    </section>
                </blockquote>
                <script async src="https://www.tiktok.com/embed.js"></script>
            </div>

        @elseif($isInstagram)
            {{-- Instagram Embed --}}
            <div class="relative w-full" style="aspect-ratio: 9/16; max-height: 600px;">
                <blockquote 
                    class="instagram-media w-full h-full rounded-2xl shadow-2xl" 
                    data-instgrm-permalink="{{ $videoUrl }}"
                    data-instgrm-version="14"
                    style="background:#FFF; border:0; border-radius:24px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);"
                >
                </blockquote>
                <script async src="//www.instagram.com/embed.js"></script>
            </div>

        @else
            {{-- Lien non reconnu --}}
            <div class="flex items-center justify-center w-full bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-lg p-8" style="aspect-ratio: 9/16; max-height: 600px;">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Format de vidéo non pris en charge</p>
                    <a href="{{ $videoUrl }}" target="_blank" class="text-brand-600 hover:text-brand-700 underline">
                        Ouvrir le lien dans un nouvel onglet
                    </a>
                </div>
            </div>
        @endif

    @else
        {{-- Aucune vidéo disponible --}}
        <div class="flex items-center justify-center w-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-12" style="aspect-ratio: 9/16; max-height: 600px;">
            <div class="text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Aucune vidéo disponible</p>
            </div>
        </div>
    @endif
</div>
