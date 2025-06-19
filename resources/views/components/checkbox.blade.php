@props([
    'label' => null,
    'name',
    'checked' => false,
    'required' => false,
])

<div class="flex flex-col items-start gap-1">
    @if($label)
        <span class="text-sm font-medium text-neutral-dark dark:text-gray-300 mb-1">{{ $label }}@if($required)<span class="text-red-500">*</span>@endif</span>
    @endif
    <input type="hidden" name="{{ $name }}" value="0">
    <label class="inline-flex items-center cursor-pointer">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            class="sr-only peer"
            @if(old($name, $checked)) checked @endif
            @if($required) required @endif
            {{ $attributes }}
        >
        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 
                    peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
                    peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] 
                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                    after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                    peer-checked:bg-primary dark:peer-checked:bg-primary">
        </div>
    </label>
</div>
