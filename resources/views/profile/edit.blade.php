<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center gap-6 border-b border-gray-800 pb-4 mb-6">
                <a href="{{ route('profile.show') }}" class="text-sm font-bold uppercase tracking-wider transition pb-2 border-b-2 {{ request()->routeIs('profile.show') ? 'text-indigo-400 border-indigo-500' : 'text-gray-500 border-transparent hover:text-gray-300' }}">
                    🎵 Mi Colección
                </a>
                <a href="{{ route('profile.edit') }}" class="text-sm font-bold uppercase tracking-wider transition pb-2 border-b-2 {{ request()->routeIs('profile.edit') ? 'text-indigo-400 border-indigo-500' : 'text-gray-500 border-transparent hover:text-gray-300' }}">
                    ⚙️ Ajustes de Cuenta
                </a>
            </div>

            <div class="mb-4">
                <h1 class="text-3xl font-black uppercase tracking-tight text-gray-100">Configuración</h1>
                <p class="text-gray-400 text-sm">Gestiona tus credenciales de acceso, seguridad y visibilidad de cuenta.</p>
            </div>

            <div class="p-6 sm:p-8 bg-gray-900 border border-gray-800 shadow sm:rounded-xl">
                <div class="max-w-xl text-gray-200">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-900 border border-gray-800 shadow sm:rounded-xl">
                <div class="max-w-xl text-gray-200">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-900 border border-red-950 shadow sm:rounded-xl bg-gradient-to-br from-gray-900 to-red-950/10">
                <div class="max-w-xl text-gray-200">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
