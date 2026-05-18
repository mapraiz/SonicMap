<x-app-layout>
    <div class="py-12 text-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ route('search.index') }}" method="GET" class="mb-8">
                <input type="text" name="query" value="{{ $searchTerm }}"
                       class="w-full bg-gray-800 border-gray-700 text-white rounded-lg p-3"
                       placeholder="Buscar álbumes...">
            </form>

            @if($results && $results->count() > 0)
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 space-y-4">
                    <div class="mb-6 text-sm text-gray-400 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
                        <p>
                            Mostrando resultados <span class="font-bold text-white">{{ $results->firstItem() }}</span>
                            al <span class="font-bold text-white">{{ $results->lastItem() }}</span>
                            de <span class="font-bold text-indigo-400">{{ $results->total() }}</span> álbumes.
                        </p>

                    </div>
                   @foreach($results as $album)
                        <div class="p-4 bg-gray-900 rounded-lg flex items-center justify-between border border-gray-800 hover:border-gray-700 transition gap-4">

                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-16 h-16 bg-gray-800 rounded overflow-hidden flex-shrink-0 border border-gray-700 shadow-md">
                                    <img
                                        src="https://coverartarchive.org/release-group/{{ $album['id'] }}/front-250"
                                        alt="Portada de {{ $album['title'] }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                        onerror="this.src='https://placehold.co/100x100/1f2937/9ca3af?text=—'; this.onerror=null;"
                                    >
                                </div>

                                <div class="min-w-0">
                                    <h3 class="font-bold text-lg text-gray-100 truncate pr-2" title="{{ $album['title'] }}">
                                        {{ $album['title'] }}
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-x-2 text-sm text-gray-400 mt-0.5">
                                        <span class="text-indigo-400 font-medium">
                                            {{ $album['artist-credit'][0]['name'] ?? 'Artista desconocido' }}
                                        </span>

                                        {{-- Separation dot --}}
                                        @if(isset($album['first-release-date']))
                                            <span class="text-gray-600">•</span>
                                            <span class="bg-gray-800 text-gray-300 text-xs px-2 py-0.5 rounded font-mono">
                                                {{ substr($album['first-release-date'], 0, 4) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <a href="{{ route('albums.show', $album['id']) }}"
                                class="bg-indigo-600 px-4 py-2 rounded text-sm font-bold hover:bg-indigo-500 transition block shadow">
                                    Ver Detalles
                                </a>
                            </div>

                        </div>
                    @endforeach

                    <div class="mt-6 pt-4 border-t border-gray-700 text-gray-400">
                        {{ $results->links() }}
                    </div>
                </div>
            @elseif($searchTerm)
                <p class="text-gray-500 italic text-center">No se encontraron resultados para "{{ $searchTerm }}".</p>
            @endif

        </div>
    </div>
</x-app-layout>
