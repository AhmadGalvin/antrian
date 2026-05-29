@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg bg-primary/15 text-primary transition-all duration-200'
            : 'flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
