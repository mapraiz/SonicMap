<!--<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <circle cx="50" cy="45" r="35" stroke="currentColor" stroke-width="1.5" fill="none" opacity="0.3" stroke-dasharray="2 2" />
    <circle cx="50" cy="45" r="25" stroke="currentColor" stroke-width="2" fill="none" opacity="0.6" />

    <path d="M50 88C35 70 22 55 22 42C22 26 34 14 50 14C66 14 78 26 78 42C78 55 65 70 50 88Z"
          stroke="currentColor"
          stroke-width="5"
          fill="none"
          stroke-linejoin="round"/>

    <path d="M50 25 L58 45 L50 50 L42 45 Z" fill="currentColor" />
    <path d="M50 50 L58 45 L50 65 L42 45 Z" stroke="currentColor" stroke-width="1.5" fill="none" />

    <circle cx="50" cy="45" r="3" fill="currentColor" />
</svg>-->
<img src="{{ asset('images/logo.png') }}" {{ $attributes->merge(['class' => 'block h-9 w-auto']) }} alt="SonicMap Logo">
