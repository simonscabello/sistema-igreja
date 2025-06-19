@props([
    'label' => null,
    'name',
    'checked' => false,
    'required' => false,
])

<div class="flex items-center gap-2">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        @if(old($name, $checked)) checked @endif
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'form-checkbox rounded text-primary focus:ring-primary']) }}
    >
    @if($label)
        <label for="{{ $name }}" class="text-md font-bold text-gray-700 cursor-pointer select-none">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
</div>
