@props([
    'label' => null,
    'name',
    'value' => '',
    'required' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-bold text-gray-700 mb-1">
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
        {{ $attributes->merge(['class' => 'form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 sm:text-sm']) }}>{{ old($name, $value) }}</textarea>
</div>
