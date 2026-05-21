<x-app-layout>
    <div class="py-12 text-white bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- SEARCH ENGINE HERO CONTAINER --}}
            <div class="bg-gradient-to-r from-gray-800 to-indigo-950 p-8 rounded-2xl border border-gray-700 shadow-2xl relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <h1 class="text-3xl font-black tracking-tight mb-2 flex items-center gap-2">
                        🔍 Explorar Música
                    </h1>
                    <p class="text-gray-300 text-sm mb-6">
                        Introduce el nombre de tus discos favoritos para consultarlos, calificarlos y añadirlos a tu biblioteca.
                    </p>

                    <form action="{{ route('search.index') }}" method="GET" class="relative max-w-xl">
                        <input type="text" name="query" value="{{ $searchTerm }}"
                               class="w-full bg-gray-900/90 border border-gray-700 rounded-xl py-3.5 pl-5 pr-14 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-inner"
                               placeholder="Buscar álbumes por título...">
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 hover:text-indigo-400 font-bold transition">
                            ➔
                        </button>
                    </form>
                </div>
                <div class="absolute right-0 bottom-0 top-0 w-1/3 bg-[radial-gradient(circle_at_bottom_right,rgba(99,102,241,0.1),transparent)] pointer-events-none"></div>
            </div>

            {{-- SEARCH RESULTS SECTION --}}
            @if($results && $results->count() > 0)
                <div class="space-y-4">
                    {{-- METRICS HEADER BADGE --}}
                    <div class="text-sm text-gray-400 bg-gray-900 border border-gray-800 rounded-xl px-5 py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 shadow-md">
                        <p>
                            Mostrando <span class="font-bold text-white">{{ $results->firstItem() }}</span>–<span class="font-bold text-white">{{ $results->lastItem() }}</span>
                            de <span class="font-bold text-indigo-400">{{ $results->total() }}</span> álbumes encontrados.
                        </p>
                    </div>

                    {{-- CARDS GRID LAYOUT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($results as $album)
                            <div class="p-4 bg-gray-800 rounded-xl flex items-center justify-between border border-gray-700 hover:border-indigo-500 hover:shadow-indigo-950/20 shadow transition duration-200 gap-4 group">

                                <div class="flex items-center gap-4 min-w-0">
                                    {{-- ALBUM COVER ART WITH HOVER EFFECT --}}
                                    <div class="w-20 h-20 bg-gray-900 rounded-lg overflow-hidden flex-shrink-0 border border-gray-700 shadow-md group-hover:scale-[1.03] transition duration-200">
                                        <img
                                            src="https://coverartarchive.org/release-group/{{ $album['id'] }}/front-250"
                                            alt="Portada de {{ $album['title'] }}"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                            onerror="this.src='https://placehold.co/250x250/111827/4b5563?text=💽'; this.onerror=null;"
                                        >
                                    </div>

                                    {{-- ALBUM INFORMATION METADATA --}}
                                    <div class="min-w-0">
                                        <h3 class="font-black text-base text-gray-100 truncate pr-2 group-hover:text-indigo-400 transition" title="{{ $album['title'] }}">
                                            {{ $album['title'] }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-x-2 text-sm text-gray-400 mt-1">
                                            <span class="text-gray-300 font-medium">
                                                {{ $album['artist-credit'][0]['name'] ?? 'Artista desconocido' }}
                                            </span>

                                            @if(isset($album['first-release-date']))
                                                <span class="text-gray-600">•</span>
                                                <span class="bg-gray-950 text-indigo-400 text-xs px-2 py-0.5 rounded font-mono font-bold border border-gray-800">
                                                    {{ substr($album['first-release-date'], 0, 4) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION BUTTON --}}
                                <div class="flex-shrink-0">
                                    <a href="{{ route('albums.show', $album['id']) }}"
                                       class="bg-gray-900 hover:bg-indigo-600 border border-gray-700 hover:border-indigo-500 px-4 py-2.5 rounded-xl text-xs font-bold text-gray-300 hover:text-white transition block shadow-sm whitespace-nowrap">
                                        Ver Detalles
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- TAILWIND PAGINATION CONTEXT INTERFACE --}}
                    <div class="mt-8 pt-4 border-t border-gray-800 custom-pagination">
                        {{ $results->links() }}
                    </div>
                </div>
            @elseif($searchTerm)
                {{-- BEAUTIFUL EMPTY STATE NOTICE --}}
                <div class="bg-gray-800 rounded-2xl p-12 border border-gray-700 text-center max-w-xl mx-auto shadow-xl mt-12">
                    <div class="text-4xl mb-4">📭</div>
                    <h3 class="text-lg font-bold text-gray-200 mb-1">Sin resultados</h3>
                    <p class="text-gray-400 text-sm">
                        No pudimos encontrar ningún registro que coincida con "<span class="text-indigo-400 font-mono">{{ $searchTerm }}</span>". Revisa la ortografía o intenta con otro término.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
