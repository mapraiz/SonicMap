<x-app-layout>
    <div class="py-12 text-white bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">



            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- CARD: ALBUMS --}}
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-xl text-indigo-400">💽</div>
                    <div>
                        <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Discos Oídos</p>
                        <h3 class="text-2xl font-black mt-0.5">{{ $totalAlbums }}</h3>
                    </div>
                </div>

                {{-- CARD: SONGS --}}
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-xl text-emerald-400">🎵</div>
                    <div>
                        <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Canciones Calificadas</p>
                        <h3 class="text-2xl font-black mt-0.5">{{ $totalSongs }}</h3>
                    </div>
                </div>

                {{-- CARD: AVERAGE RATING --}}
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-xl text-yellow-400">⭐</div>
                    <div>
                        <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Nota Media Global</p>
                        <h3 class="text-2xl font-black mt-0.5">
                            {{ $averageScore ? number_format($averageScore, 1) : '—' }} <span class="text-xs text-gray-500 font-normal">/ 5</span>
                        </h3>
                    </div>
                </div>
            </div>

            {{-- MAIN GRID SECTIONS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">
                    <h2 class="text-lg font-black uppercase tracking-wider text-gray-400 flex items-center gap-2">
                        🕒 Actividad Reciente
                    </h2>

                    @if($recentReviews->isEmpty())
                        <div class="bg-gray-800 p-8 rounded-xl border border-gray-700 text-center text-gray-500 text-sm">
                            Aún no has calificado nada. ¡Busca una canción o disco para empezar!
                        </div>
                    @else
                        <div class="bg-gray-800 rounded-xl border border-gray-700 divide-y divide-gray-700 shadow-xl overflow-hidden">
                            @foreach($recentReviews as $review)
                                <div class="p-4 sm:p-5 flex items-start justify-between gap-4 hover:bg-gray-700/30 transition">
                                    <div class="flex items-start gap-3.5">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 border
                                            {{ $review->reviewable_type === 'album' ? 'bg-indigo-950/50 text-indigo-400 border-indigo-800/40' : 'bg-emerald-950/50 text-emerald-400 border-emerald-800/40' }}">
                                            {{ $review->reviewable_type === 'album' ? 'DISCO' : 'TEMA' }}
                                        </div>

                                        <div>
                                            @php
                                                $routeName = $review->reviewable_type === 'album' ? 'albums.show' : 'songs.show';

                                                $itemId = $review->reviewable?->mbid;
                                            @endphp

                                            @if($itemId)
                                                <a href="{{ route($routeName, ['mbid' => $itemId]) }}" class="font-bold text-white hover:text-indigo-400 block transition leading-snug">
                                                    {{ $review->reviewable?->title ?? 'Título Desconocido' }}
                                                </a>
                                            @else
                                                <span class="font-bold text-gray-400 block leading-snug cursor-not-allowed">
                                                    {{ $review->reviewable?->title ?? 'Título Desconocido' }} (Sin ID)
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-0.5 bg-gray-900 px-2 py-1 rounded border border-gray-700/50 shrink-0">
                                        @foreach(range(1, 5) as $star)
                                            <div class="relative w-3.5 h-3.5 flex items-center justify-center">
                                                <svg class="absolute w-full h-full text-gray-700 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/>
                                                </svg>
                                                <div class="absolute top-0 left-0 h-full overflow-hidden pointer-events-none"
                                                     style="width: {{ $review->rating >= $star ? '100%' : ($review->rating == $star - 0.5 ? '50%' : '0%') }};">
                                                    <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>



            </div>
        </div>
    </div>
</x-app-layout>
