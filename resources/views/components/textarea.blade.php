@props([
    'label' => null,
    'name',
    'value' => '',
    'required' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-md font-bold text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'form-textarea block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm
         focus:border-primary focus:ring-primary sm:text-sm bg-white dark:bg-gray-700 text-neutral-dark dark:text-white']) }}>{{ old($name, $value) }}</textarea>
</div>
