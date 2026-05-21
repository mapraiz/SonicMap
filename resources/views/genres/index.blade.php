<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-black uppercase tracking-tight text-white mb-2">Explorar por Géneros</h1>
                <p class="text-gray-400 text-lg">Selecciona una corriente sonora para ver los lanzamientos más influyentes del archivo global.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($genres as $genre)
                    <a href="{{ route('genres.show', $genre['slug']) }}"
                       class="group bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-indigo-500 transition-all duration-300 flex flex-col justify-between shadow-lg hover:shadow-indigo-950/20">
                        <div>
                            <div class="text-4xl mb-4 transform group-hover:scale-110 transition-transform duration-200">
                                {{ $genre['icon'] }}
                            </div>

                            <h2 class="text-xl font-bold text-gray-100 group-hover:text-indigo-400 transition-colors">
                                {{ $genre['name'] }}
                            </h2>

                            <p class="text-gray-400 text-sm mt-2 leading-relaxed">
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
