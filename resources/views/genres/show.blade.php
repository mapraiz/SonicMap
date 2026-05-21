<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 border-b border-gray-900 pb-6">
                <a href="{{ route('genres.index') }}" class="text-xs font-bold uppercase tracking-widest text-indigo-400 hover:text-indigo-300 transition flex items-center gap-1">
                    <span>←</span> Volver a Géneros
                </a>
                <h1 class="text-4xl font-black uppercase tracking-tight mt-2 text-gray-100">
                    Explorando: <span class="text-indigo-500">{{ $genreName }}</span>
                </h1>
                <p class="text-gray-400 text-sm mt-1">Los lanzamientos más populares indexados en el archivo global de MusicBrainz.</p>
            </div>

            @if(count($albums) > 0)
                <div class="flex justify-center md:justify-start">

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-4 w-full">
                        @foreach($albums as $album)

                            <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 flex flex-col justify-between hover:border-gray-700 transition duration-200 group shadow-md w-full max-w-[150px] sm:max-w-[160px] mx-auto sm:mx-0">

                                <div>
                                    <div class="w-full aspect-square bg-gray-950 rounded-lg overflow-hidden border border-gray-800 mb-3 relative flex items-center justify-center max-w-[136px] max-h-[136px] mx-auto">
                                        <img src="https://coverartarchive.org/release-group/{{ $album['id'] }}/front-250"
                                            alt="Portada"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                            loading="lazy"
                                            onerror="this.src='https://placehold.co/250x250/1f2937/9ca3af?text=—'">
                                    </div>

                                    <h3 class="font-bold text-gray-200 text-xs leading-snug line-clamp-2 h-8 group-hover:text-indigo-400 transition-colors" title="{{ $album['title'] }}">
                                        {{ $album['title'] }}
                                    </h3>

                                    <p class="text-[11px] text-gray-400 truncate mt-0.5">
                                        {{ $album['artist-credit'][0]['name'] ?? 'Artista' }}
                                    </p>
                                </div>

                                <a href="{{ route('albums.show', $album['id']) }}"
                                class="mt-3 block text-center bg-gray-950 border border-gray-800 hover:bg-indigo-600 hover:border-indigo-600 text-[11px] font-bold py-1.5 rounded-md text-gray-300 hover:text-white transition duration-200 w-full">
                                    Ver Detalles
                                </a>

                            </div>
                        @endforeach
                    </div>

                </div>
            @else
                <div class="text-center py-20 bg-gray-900 border border-gray-800 rounded-xl p-8">
                    <p class="text-gray-500 italic text-lg">No se encontraron álbumes cargados bajo esta etiqueta actualmente.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
