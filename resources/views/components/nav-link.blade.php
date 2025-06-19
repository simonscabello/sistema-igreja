@props(['active'])

@php
$classes = $active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary text-sm font-medium leading-5 text-neutral-dark dark:text-gray-300 focus:outline-none focus:border-primary-dark transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-dark dark:text-gray-300 hover:text-primary focus:outline-none focus:border-primary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
