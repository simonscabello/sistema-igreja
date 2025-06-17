@props(['active' => false])

@php
$classes = $active
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary text-start text-base font-medium text-primary-dark bg-primary-light focus:outline-none focus:text-primary-dark focus:bg-primary-light focus:border-primary-dark transition duration-150 ease-in-out'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-neutral-dark hover:text-primary hover:bg-neutral-light focus:outline-none focus:text-primary focus:bg-neutral-light focus:border-primary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
