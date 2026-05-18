<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-black uppercase tracking-tight text-white mb-2">Explorar por Géneros</h1>
                <p class="text-gray-400 text-lg">Catálogo oficial sincronizado en tiempo real desde la base de datos global de MusicBrainz.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($genres as $genre)
                    <a href="{{ route('search.index', ['query' => $genre['slug']]) }}"
                       class="group bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-indigo-500 transition-all duration-300 flex flex-col justify-between shadow-lg hover:shadow-indigo-950/20">
                        <div>
                            <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center mb-4 text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>

                            <h2 class="text-xl font-bold text-gray-100 group-hover:text-indigo-400 transition-colors">
                                {{ $genre['name'] }}
                            </h2>

                            <p class="text-gray-400 text-sm mt-2 leading-relaxed line-clamp-3">
                                {{ $genre['desc'] }}
                            </p>
                        </div>

                        <div class="mt-6 flex items-center text-xs font-bold uppercase tracking-wider text-gray-500 group-hover:text-indigo-400 transition-colors">
                            <span>Ver Álbumes</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ms-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
