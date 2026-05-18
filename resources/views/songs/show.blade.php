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

               <div x-data="{
                    hoverRating: 0,
                    currentRating: {{ $averageRating ?? 0 }},
                    getDisplayRating() { return this.hoverRating || this.currentRating }
                }" class="flex flex-col items-center">

                    <p class="mb-4 font-bold text-gray-200">Tu Puntuación</p>

                    <form id="ratingForm" action="{{ route('reviews.store') }}" method="POST" class="flex gap-1">
                        @csrf
                        <input type="hidden" name="mbid" value="{{ $song['id'] }}">
                        <input type="hidden" name="item_type" value="song">
                        <input type="hidden" name="rating" :value="hoverRating || currentRating">

                        <template x-for="star in [1, 2, 3, 4, 5]">
                            <div class="relative cursor-pointer text-4xl"
                                @mousemove="
                                    let rect = $el.getBoundingClientRect();
                                    hoverRating = ( $event.clientX - rect.left < rect.width / 2 ) ? star - 0.5 : star;
                                "
                                @mouseleave="hoverRating = 0"
                                @click="$nextTick(() => $el.closest('form').submit())">

                                <span class="text-gray-700">★</span>

                                <div class="absolute inset-0 overflow-hidden text-yellow-400"
                                    :style="'width: ' + (getDisplayRating() >= star ? '100%' : (getDisplayRating() >= star - 0.5 ? '50%' : '0%'))">
                                    ★
                                </div>
                            </div>
                        </template>
                    </form>

                    <p class="mt-2 text-sm text-gray-500" x-text="getDisplayRating() > 0 ? getDisplayRating() + ' / 5' : 'Selecciona una nota'"></p>
                </div>

                @if(isset($song['releases'][0]))
                <div class="mt-12 pt-8 border-t border-gray-700">
                    <p class="text-gray-500 text-sm mb-2">Aparece en el álbum:</p>
                    <a href="{{ route('albums.show', $song['releases'][0]['id']) }}"
                       class="text-white hover:text-indigo-400 font-bold transition">
                        {{ $song['releases'][0]['title'] }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
