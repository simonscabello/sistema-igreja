@props([
    'label' => null,
    'name',
    'value' => null,
    'required' => false,
])

<div>
    @if($label)
        <x-input-label :value="$label" />
    @endif
    <input
        type="datetime-local"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value ? $value->format('Y-m-d\TH:i') : '') }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 bg-white dark:bg-gray-700 text-neutral-dark dark:text-white']) }}
    >
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
