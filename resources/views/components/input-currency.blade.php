@props([
    'label' => null,
    'name',
    'value' => null,
    'prefix' => 'R$',
    'required' => false,
])

<div>
    @if($label)
        <x-input-label :value="$label" />
    @endif
    <div class="relative mt-1 rounded-md shadow-sm">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <span class="text-gray-500 sm:text-sm">{{ $prefix }}</span>
        </div>
        <input
            type="text"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value ? number_format($value, 2, ',', '.') : '') }}"
            @if($required) required @endif
            x-data
            x-mask:dynamic="$money($input, ',', '.', 2)"
            {{ $attributes->merge(['class' => 'block w-full rounded-md border-neutral-medium pl-8 pr-12 focus:border-primary focus:ring-primary sm:text-sm']) }}
        >
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
