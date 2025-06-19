@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'flex items-center px-3 py-2 text-md font-medium rounded-md transition ' .
       ($active ? 'bg-primary-light/40 dark:bg-primary/20 text-primary-dark dark:text-primary border-l-4 border-primary' : 'text-neutral-dark dark:text-gray-300 hover:bg-neutral-light dark:hover:bg-gray-700 hover:text-primary border-l-4 border-transparent')]) }}>
    <div class="flex items-center flex-1 gap-2">
        <div class="w-5 h-5">
            {{ $icon ?? '' }}
        </div>
        <span>{{ $slot }}</span>
    </div>
</a>
