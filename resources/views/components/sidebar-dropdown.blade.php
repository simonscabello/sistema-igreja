@props(['title', 'icon', 'active' => false])

<div x-data="{ open: @js($active) }" class="relative">
    <button @click="open = !open" class="w-full px-3 py-2 text-md font-medium rounded-md text-neutral-dark dark:text-gray-300 hover:bg-neutral-light dark:hover:bg-gray-700 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary transition flex items-center border-l-4 {{ $active ? 'bg-primary-light/40 dark:bg-primary/20 text-primary-dark dark:text-primary border-primary' : 'border-transparent' }}">
        <div class="flex items-center flex-1 gap-2">
            @if($icon === 'dollar-sign')
                <span class="w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            @endif
            <span>{{ $title }}</span>
        </div>
        <span class="ml-auto flex items-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="mt-1 space-y-1 pl-4">
        {{ $slot }}
    </div>
</div>
