@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pr-4 py-2 border-l-4 border-indigo-500 text-start text-base font-medium text-white bg-indigo-950/30 focus:outline-none focus:text-indigo-200 focus:bg-indigo-950/50 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pr-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 hover:text-gray-200 hover:bg-gray-800 hover:border-gray-700 focus:outline-none focus:text-gray-200 focus:bg-gray-800 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
