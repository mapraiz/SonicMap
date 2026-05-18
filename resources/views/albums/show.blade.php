<x-app-layout>
    <div class="py-12 text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

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

                            <div x-data="{ showReviewModal: false, isRegistered: false }">
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mbid" value="{{ $album['id'] }}">
                                    <input type="hidden" name="item_type" value="album">
                                    <input type="hidden" name="rating" value="0"> {{-- 0 means 'just logged, no rating yet' --}}


                                    <button type="submit"
                                            class="w-full mt-8 {{ $isLogged ? 'bg-green-700' : 'bg-indigo-600' }} flex items-center justify-center gap-2 hover:bg-indigo-500 text-white py-3 rounded-lg font-bold transition shadow-lg">
                                        {{ $isLogged ? '✓ En tu Logbook' : '+ Registrar en Logbook' }}
                                    </button>
                                </form>

                                <button @click="showReviewModal = true"
                                        class="w-full mt-3 border border-gray-600 hover:border-indigo-500 hover:text-indigo-400 text-gray-300 py-3 rounded-lg font-bold transition">
                                    Escribir Reseña
                                </button>

                                <x-modal name="review-modal" ...>
                                    </x-modal>
                            </div>
                        </div>
                    </div>
                </div>{{-- END OF LEFT COLUMN --}}

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
                                            <a href="{{ route('song.show', $track['recording']['id']) }}" class="hover:text-indigo-400 transition">
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
