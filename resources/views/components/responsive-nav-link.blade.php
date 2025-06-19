@props(['active' => false])

@php
$classes = $active
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary text-start text-base font-medium text-primary-dark dark:text-primary bg-primary-light dark:bg-primary/20 focus:outline-none focus:text-primary-dark dark:focus:text-primary focus:bg-primary-light dark:focus:bg-primary/20 focus:border-primary-dark transition duration-150 ease-in-out'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-neutral-dark dark:text-gray-300 hover:text-primary hover:bg-neutral-light dark:hover:bg-gray-700 focus:outline-none focus:text-primary focus:bg-neutral-light dark:focus:bg-gray-700 focus:border-primary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
