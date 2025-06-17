@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'flex items-center px-4 py-2 text-sm font-medium rounded-md ' .
       ($active ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900')]) }}>
    <div class="flex items-center flex-1">
        <div class="w-5 h-5 mr-3 text-gray-400">
            {{ $icon }}
        </div>
        <span>{{ $slot }}</span>
    </div>
</a>
