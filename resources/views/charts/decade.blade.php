<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <nav class="text-sm text-gray-500 mb-4 uppercase tracking-widest">
                <span class="text-gray-400">Listas</span>
                <span class="mx-2">/</span>
                <span class="text-indigo-400 font-bold">Años {{ $decade }}s</span>
            </nav>

            <h1 class="text-4xl font-black uppercase mb-8">Cápsula de Tiempo: Los {{ $decade }}s</h1>

            @if(count($albums) > 0)
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 space-y-4">
                    @foreach($albums as $index => $album)
                        <div class="p-4 bg-gray-950 rounded-lg border border-gray-800 flex items-center justify-between gap-4 hover:border-gray-700 transition">

                            <div class="flex items-center gap-4 min-w-0">
                                <span class="text-xl font-black text-gray-600 font-mono w-6 text-center">
                                    #{{ $index + 1 }}
                                </span>

                                <div class="w-12 h-12 bg-gray-800 rounded overflow-hidden flex-shrink-0 border border-gray-700">
                                    <img src="https://coverartarchive.org/release-group/{{ $album['id'] }}/front-250"
                                         alt="Portada"
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         onerror="this.src='https://placehold.co/100x100/1f2937/9ca3af?text=—'">
                                </div>

                                <div class="min-w-0">
                                    <h3 class="font-bold text-lg text-gray-100 truncate">{{ $album['title'] }}</h3>
                                    <p class="text-indigo-400 text-sm truncate">
                                        {{ $album['artist-credit'][0]['name'] ?? 'Artista Desconocido' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 flex-shrink-0">
                                @if(isset($album['first-release-date']))
                                    <span class="text-xs font-mono bg-gray-900 px-2 py-1 rounded text-gray-400 border border-gray-800">
                                        {{ substr($album['first-release-date'], 0, 4) }}
                                    </span>
                                @endif

                                <a href="{{ route('albums.show', $album['id']) }}"
                                   class="bg-indigo-600 px-4 py-1.5 rounded text-xs font-bold hover:bg-indigo-500 transition shadow">
                                    Ver Álbum
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-xl p-8">
                    <p class="text-gray-500 italic text-lg">No pudimos conectar con el archivo histórico de MusicBrainz.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
