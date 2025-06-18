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
        {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-medium rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50']) }}
    >
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
