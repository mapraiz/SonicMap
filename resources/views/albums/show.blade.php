<x-app-layout>
    <div class="py-12 text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- LEFT COLUMN: COVER ART & ACTIONS --}}
                <div class="md:col-span-1">
                    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-xl h-fit">

                        <div class="flex justify-center mb-6">
                            <div class="w-48 h-48 sm:w-64 sm:h-64 flex-shrink-0 bg-gray-900 rounded-lg overflow-hidden border border-gray-700 shadow-2xl">
                                <img
                                    src="https://coverartarchive.org/release-group/{{ $album['id'] }}/front-500"
                                    alt="Portada de {{ $album['title'] }}"
                                    class="w-full h-full object-cover shadow-lg"
                                    onerror="this.src='https://placehold.co/400x400/1f2937/9ca3af?text=Sin+Portada'; this.onerror=null;"
                                >
                            </div>
                        </div>

                        <div class="text-center md:text-left">
                            <h1 class="text-2xl font-bold mb-1 leading-tight">{{ $album['title'] ?? 'Título no disponible' }}</h1>

                            <p class="text-indigo-400 text-lg mb-4 font-semibold">
                                {{ $album['artist-credit'][0]['name'] ?? 'Artista Desconocido' }}
                            </p>

                            <div class="space-y-3 text-sm text-gray-400 border-t border-gray-700 pt-4">
                                <p class="flex justify-between">
                                    <span class="font-bold text-gray-200">Año:</span>
                                    <span>{{ isset($album['first-release-date']) ? substr($album['first-release-date'], 0, 4) : 'N/A' }}</span>
                                </p>

                                <p class="flex justify-between">
                                    <span class="font-bold text-gray-200">Género:</span>
                                    <span>{{ $album['genres'][0]['name'] ?? 'No especificado' }}</span>
                                </p>

                                <div class="mt-6">
                                    <p class="text-gray-200 font-bold mb-1">Puntuación SonicMap:</p>
                                    <span class="text-2xl text-yellow-400 font-bold">
                                        {{ $averageRating ? number_format($averageRating, 1) . ' / 5' : 'Sin notas' }}
                                    </span>
                                </div>
                            </div>

                            {{-- THE CRITICAL FIX: Declared rating and hoverRating here in x-data --}}
                            <div x-data="{ showReviewModal: false, isRegistered: false, rating: 0, hoverRating: 0 }">
                                <form action="{{ route('albums.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mbid" value="{{ $album['id'] }}">
                                    <input type="hidden" name="title" value="{{ $album['title'] ?? 'Título no disponible' }}">
                                    <input type="hidden" name="release_date" value="{{ $album['first-release-date'] ?? '' }}">

                                    <input type="hidden" name="artist_mbid" value="{{ $album['artist-credit'][0]['artist']['id'] ?? '' }}">
                                    <input type="hidden" name="artist_name" value="{{ $album['artist-credit'][0]['name'] ?? 'Artista Desconocido' }}">

                                    <button type="submit"
                                            {{ $isLogged ? 'disabled' : '' }}
                                            class="w-full mt-8 {{ $isLogged ? 'bg-green-700 cursor-not-allowed opacity-85' : 'bg-indigo-600 hover:bg-indigo-500' }} flex items-center justify-center gap-2 text-white py-3 rounded-lg font-bold transition shadow-lg">
                                        {{ $isLogged ? '✓ En tu Logbook' : '+ Registrar en Logbook' }}
                                    </button>
                                </form>

                                <button @click="showReviewModal = true"
                                        class="w-full mt-3 border border-gray-600 hover:border-indigo-500 hover:text-indigo-400 text-gray-300 py-3 rounded-lg font-bold transition">
                                    Escribir Reseña
                                </button>

                                <div x-show="showReviewModal"
                                     x-transition.opacity
                                     class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
                                     style="display: none;">

                                    <div @click.away="showReviewModal = false"
                                         x-transition.scale
                                         class="bg-gray-900 border border-gray-800 rounded-2xl max-w-md w-full p-6 shadow-2xl relative text-left">

                                        <button @click="showReviewModal = false" type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-300 text-xl font-bold">&times;</button>

                                        <h3 class="text-xl font-black text-gray-100 uppercase tracking-tight mb-1">Escribir Reseña</h3>
                                        <p class="text-gray-400 text-xs mb-6">{{ $album['title'] }} — {{ $album['artist-credit'][0]['name'] ?? '' }}</p>

                                        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-5">
                                            @csrf
                                            <input type="hidden" name="mbid" value="{{ $album['id'] }}">
                                            <input type="hidden" name="title" value="{{ $album['title'] ?? 'Título no disponible' }}">
                                            <input type="hidden" name="artist_mbid" value="{{ $album['artist-credit'][0]['artist']['id'] ?? '' }}">
                                            <input type="hidden" name="artist_name" value="{{ $album['artist-credit'][0]['name'] ?? 'Artista Desconocido' }}">
                                            <input type="hidden" name="reviewable_type" value="album">

                                            {{-- Syncs selected Alpine dynamic index property directly with Laravel form submission payload data --}}
                                            <input type="hidden" name="rating" :value="rating">

                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tu Calificación</label>
                                                <div class="flex items-center gap-2">

                                                    <div class="flex items-center gap-1 relative select-none">
                                                        <template x-for="i in [1, 2, 3, 4, 5]">
                                                            <div class="relative w-7 h-7 flex items-center justify-center">

                                                                <svg class="absolute w-full h-full text-gray-700 fill-current" viewBox="0 0 24 24">
                                                                    <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/>
                                                                </svg>

                                                                <div class="absolute top-0 left-0 h-full overflow-hidden pointer-events-none transition-all duration-75"
                                                                    :style="
                                                                        let activeRating = hoverRating || rating;
                                                                        if (activeRating >= i) return 'width: 100%';
                                                                        if (activeRating === i - 0.5) return 'width: 50%';
                                                                        return 'width: 0%';
                                                                    ">
                                                                    <svg class="w-7 h-7 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                                                        <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/>
                                                                    </svg>
                                                                </div>

                                                                <div class="absolute top-0 left-0 w-1/2 h-full cursor-pointer z-10"
                                                                    @mouseenter="hoverRating = i - 0.5"
                                                                    @mouseleave="hoverRating = 0"
                                                                    @click="rating = i - 0.5"></div>

                                                                <div class="absolute top-0 right-0 w-1/2 h-full cursor-pointer z-10"
                                                                    @mouseenter="hoverRating = i"
                                                                    @mouseleave="hoverRating = 0"
                                                                    @click="rating = i"></div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <span class="text-sm font-bold text-indigo-400 font-mono ml-1 bg-gray-950 px-2 py-1 rounded border border-gray-800"
                                                        x-text="rating > 0 ? rating.toFixed(1) + '/5' : 'Sin nota'">
                                                    </span>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Comentario Reseña (Opcional)</label>
                                                <textarea name="review_text"
                                                          id="review_text"
                                                          rows="4"
                                                          placeholder="¿Qué te pareció este lanzamiento? Cuenta tus impresiones..."
                                                          class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-gray-200 focus:outline-none focus:border-indigo-500 placeholder-gray-600 transition resize-none"></textarea>
                                            </div>

                                            <div class="flex items-center justify-end gap-3 pt-2">
                                                <button type="button"
                                                        @click="showReviewModal = false"
                                                        class="px-4 py-2 text-sm font-bold text-gray-400 hover:text-gray-200 transition">
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                        class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                                                    Guardar Reseña
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> {{-- END OF LEFT COLUMN --}}

                {{-- RIGHT COLUMN: TRACKLIST & COMMUNITY --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
                        <h2 class="text-xl font-bold mb-4 border-b border-gray-700 pb-2">Lista de Canciones</h2>

                        @php
                            $tracks = $album['tracks_data'] ?? null;
                        @endphp

                        @if($tracks)
                            <ul class="divide-y divide-gray-700">
                                @foreach($tracks as $track)
                                    <li class="py-3 flex justify-between items-center">
                                        <span class="text-gray-200">
                                            <span class="text-gray-500 mr-2">{{ $track['number'] ?? '?' }}.</span>
                                            <a href="{{ route('songs.show', $track['recording']['id']) }}" class="hover:text-indigo-400 transition">
                                                {{ $track['title'] }}
                                            </a>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic py-4 text-center">Información de pistas no disponible para esta edición.</p>
                        @endif
                    </div>

                    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
                        <h2 class="text-xl font-bold mb-4 border-b border-gray-700 pb-2">Reseñas de la Comunidad</h2>
                        @forelse($localAlbum->reviews ?? [] as $review)
                            <div class="mb-4 p-4 bg-gray-900 rounded-lg border-l-4 border-indigo-500 shadow">
                                <p class="text-sm font-bold text-indigo-300">{{ $review->user->name }}</p>
                                <p class="text-yellow-400 text-xs">⭐ {{ $review->rating }}/5</p>
                                <p class="italic text-gray-300 mt-2">"{{ $review->review_text }}"</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-6 italic">Sé el primero en reseñar este álbum.</p>
                        @endforelse
                    </div>
                </div> {{-- END OF RIGHT COLUMN --}}

            </div> {{-- END OF GRID --}}
        </div>
    </div>
</x-app-layout>
