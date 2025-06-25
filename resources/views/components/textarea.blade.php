@props([
    'label' => null,
    'name',
    'value' => '',
    'required' => false,
])

<div>
    @if($label)
        <x-input-label :value="$label" :required="$required" />
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'form-textarea block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm
         focus:border-primary focus:ring-primary sm:text-sm bg-white dark:bg-gray-700 text-neutral-dark dark:text-white']) }}>{{ old($name, $value) }}</textarea>
</div>
