<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="flex items-center gap-4 text-center md:text-left flex-col md:flex-row w-full md:w-auto">
                    <div class="w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center text-3xl font-black uppercase tracking-wider shadow-md text-indigo-100 flex-shrink-0">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <div class="space-y-1">
                        <h1 class="text-3xl font-black text-gray-100 uppercase tracking-tight leading-tight">{{ $user->name }}</h1>
                        <p class="text-gray-400 text-sm block">Melómano desde {{ $user->created_at->format('M Y') }}</p>

                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center gap-1.5 bg-gray-950 border border-gray-800 hover:bg-gray-800 hover:text-indigo-400 text-xs font-bold px-3 py-1.5 rounded-lg text-gray-400 transition duration-200 mt-1 shadow-sm mx-auto md:mx-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Editar Ajustes</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 md:gap-8 bg-gray-950 border border-gray-800 px-6 py-4 rounded-xl w-full md:w-auto flex-shrink-0">
                    <div class="text-center">
                        <span class="block text-2xl font-black text-indigo-400 font-mono">{{ $stats['total_saved'] }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Álbumes</span>
                    </div>
                    <div class="text-center border-x border-gray-800 px-4 md:px-8">
                        <span class="block text-2xl font-black text-indigo-400 font-mono">{{ $stats['total_ratings'] }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Reseñas</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-black text-yellow-500 font-mono">★ {{ number_format($stats['average_rating'], 1) }}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Media</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-900 pb-3">
                        <h2 class="text-xl font-black uppercase tracking-wider text-gray-200">Tu Biblioteca</h2>
                        <span class="text-xs font-bold text-gray-500 uppercase">Últimos añadidos</span>
                    </div>

                    @if($savedAlbums->count() > 0)
                        <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: flex-start;">
                            @foreach($savedAlbums as $album)
                                <div style="flex: 0 0 145px; width: 145px; max-width: 145px; background-color: #111827; border: 1px solid #1f2937; border-radius: 12px; padding: 10px; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;" class="group hover:border-indigo-500 transition duration-200 shadow-md">
                                    <div>
                                        <div style="width: 123px; height: 123px; background-color: #030712; border-radius: 8px; overflow: hidden; border: 1px solid #1f2937; margin-bottom: 8px; display: flex; align-items: center; justify-content: center; position: relative;">
                                            <img src="https://coverartarchive.org/release-group/{{ $album->mbid }}/front-250"
                                                 alt="Portada"
                                                 style="width: 100%; height: 100%; object-fit: cover;"
                                                 onerror="this.src='https://placehold.co/250x250/1f2937/9ca3af?text=—'">
                                        </div>
                                        <h3 class="font-bold text-gray-200 text-xs truncate" title="{{ $album->title }}">{{ $album->title }}</h3>
                                        <p class="text-[10px] text-gray-400 truncate mt-0.5">
                                            {{ $album->artist->name ?? 'Artista Desconocido' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('albums.show', $album->mbid) }}" class="mt-3 block text-center bg-gray-950 border border-gray-800 hover:bg-indigo-600 text-[10px] font-bold py-1.5 rounded-md text-gray-300 hover:text-white transition">
                                        Ver detalles
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-900 border border-gray-800 rounded-xl">
                            <p class="text-gray-500 italic text-sm">Aún no has guardado álbumes en tu biblioteca.</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="border-b border-gray-900 pb-3">
                        <h2 class="text-xl font-black uppercase tracking-wider text-gray-200">Calificaciones Recientes</h2>
                    </div>

                    @if($recentRatings->count() > 0)
                        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 divide-y divide-gray-800 space-y-3.5">
                            @foreach($recentRatings as $rating)
                                <div class="pt-3.5 first:pt-0 flex gap-3 items-start">
                                    <div class="w-10 h-10 bg-gray-950 rounded border border-gray-800 overflow-hidden flex-shrink-0">
                                        @if(isset($rating->reviewable->mbid))
                                            <img src="https://coverartarchive.org/release-group/{{ $rating->reviewable->mbid }}/front-250" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100/1f2937/9ca3af?text=—'">
                                        @else
                                            <img src="https://placehold.co/100x100/1f2937/9ca3af?text=🎵" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center text-yellow-500 text-sm tracking-tighter">
                                            @foreach(range(1, 5) as $i)
                                                <div class="relative w-3.5 h-3.5 flex items-center justify-center">
                                                    <span class="absolute text-gray-700">★</span>
                                                    <span class="absolute text-yellow-500 overflow-hidden"
                                                        style="width: {{ $rating->rating >= $i ? '100%' : ($rating->rating == $i - 0.5 ? '50%' : '0%') }};">
                                                        ★
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <p class="text-[10px] text-indigo-400 truncate">
                                            {{ $rating->reviewable->artist->name ?? 'Artista Desconocido' }}
                                        </p>

                                        @if(!empty($rating->review_text))
                                            <p class="text-gray-400 text-xs italic mt-1 line-clamp-2 bg-gray-950/50 p-2 rounded border border-gray-800/40">
                                                "{{ $rating->review_text }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-900 border border-gray-800 rounded-xl">
                            <p class="text-gray-500 italic text-sm">No has calificado ningún álbum todavía.</p>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
