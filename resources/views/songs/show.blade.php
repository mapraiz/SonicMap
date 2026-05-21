<x-app-layout>
    <div class="py-12 text-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-8 rounded-lg border border-gray-700 shadow-2xl text-center">

                <nav class="text-sm text-gray-500 mb-6 uppercase tracking-widest">
                    <a href="{{ route('search.index') }}" class="hover:text-indigo-400">Explorar</a>
                    <span class="mx-2">/</span>
                    <span>Canción</span>
                </nav>

                <h1 class="text-4xl font-black mb-2">{{ $song['title'] }}</h1>
                <p class="text-xl text-indigo-400 mb-8">{{ $song['artist-credit'][0]['name'] }}</p>

                <div class="mb-10">
                    <p class="text-gray-400 text-sm mb-2">Valoración Global</p>
                    <div class="flex justify-center items-center gap-2">
                        <span class="text-4xl text-yellow-500 font-bold">
                            {{ $averageRating ? number_format($averageRating, 1) : '—' }}
                        </span>
                        <span class="text-gray-600 text-2xl">/ 5</span>
                    </div>
                </div>


                @php
                    $userReview = $localSong ? $localSong->reviews()->where('user_id', auth()->id())->first() : null;
                    $userRating = $userReview ? $userReview->rating : 0;
                @endphp

                <div x-data="{
                    hoverRating: 0,
                    rating: {{ $userRating }},
                    getDisplayRating() { return this.hoverRating || this.rating }
                }" class="flex flex-col items-center">

                    <p class="mb-4 font-bold text-gray-200">Tu Puntuación</p>

                    <form id="ratingForm" action="{{ route('reviews.store') }}" method="POST" class="flex flex-col items-center gap-4">
                        @csrf
                        <input type="hidden" name="mbid" value="{{ $song['id'] }}">
                        <input type="hidden" name="title" value="{{ $song['title'] ?? 'Título no disponible' }}">
                        <input type="hidden" name="artist_name" value="{{ $song['artist-credit'][0]['name'] ?? 'Artista Desconocido' }}">
                        <input type="hidden" name="artist_mbid" value="{{ $song['artist-credit'][0]['artist']['id'] ?? '' }}">

                        <input type="hidden" name="reviewable_type" value="song">
                        <input type="hidden" name="rating" :value="rating">

                        <div class="flex items-center gap-1 relative select-none">
                            <template x-for="i in [1, 2, 3, 4, 5]">
                                <div class="relative w-9 h-9 flex items-center justify-center">

                                    <svg class="absolute w-full h-full text-gray-700 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/>
                                    </svg>

                                    <div class="absolute top-0 left-0 h-full overflow-hidden pointer-events-none transition-all duration-75"
                                         :style="
                                            let activeRating = getDisplayRating();
                                            if (activeRating >= i) return 'width: 100%';
                                            if (activeRating === i - 0.5) return 'width: 50%';
                                            return 'width: 0%';
                                         ">
                                        <svg class="w-9 h-9 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/>
                                        </svg>
                                    </div>

                                    <div class="absolute top-0 left-0 w-1/2 h-full cursor-pointer z-10"
                                         @mouseenter="hoverRating = i - 0.5"
                                         @mouseleave="hoverRating = 0"
                                         @click="rating = i - 0.5; $nextTick(() => $el.closest('form').submit())"></div>

                                    <div class="absolute top-0 right-0 w-1/2 h-full cursor-pointer z-10"
                                         @mouseenter="hoverRating = i"
                                         @mouseleave="hoverRating = 0"
                                         @click="rating = i; $nextTick(() => $el.closest('form').submit())"></div>
                                </div>
                            </template>
                        </div>
                    </form>

                    <p class="mt-4 text-sm font-bold text-indigo-400 font-mono bg-gray-950 px-2.5 py-1 rounded border border-gray-800"
                       x-text="getDisplayRating() > 0 ? getDisplayRating().toFixed(1) + ' / 5' : 'Selecciona una nota'"></p>
                </div>

                @if(isset($song['releases'][0]))
                <div class="mt-12 pt-8 border-t border-gray-700">
                    <p class="text-gray-500 text-sm mb-2">Aparece en el álbum:</p>
                    <p class="text-white  font-bold transition">
                        {{ $song['releases'][0]['title'] }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
