@props([
    'label' => null,
    'name',
    'options',
    'value' => null,
    'required' => false,
])

<div>
    @if($label)
        <x-input-label :value="$label" />
    @endif
    <div class="mt-2 space-y-2">
        @foreach($options as $optionValue => $optionLabel)
            <div class="flex items-center">
                <input
                    type="radio"
                    name="{{ $name }}"
                    id="{{ $name }}_{{ $optionValue }}"
                    value="{{ $optionValue }}"
                    @if(old($name, $value) == $optionValue) checked @endif
                    @if($required) required @endif
                    {{ $attributes->merge(['class' => 'form-radio text-primary focus:ring-primary']) }}
                >
                <label for="{{ $name }}_{{ $optionValue }}" class="ml-2 text-md text-gray-700 cursor-pointer select-none">
                    {{ $optionLabel }}
                </label>
            </div>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
