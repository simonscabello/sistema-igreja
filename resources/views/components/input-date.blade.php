@props([
    'label' => null,
    'name',
    'value' => null,
    'required' => false,
    'placeholder' => 'Selecione uma data',
])

<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="block text-md font-bold text-gray-700">{{ $label }}</label>
    @endif
    <div class="relative max-w-sm">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>
        </div>
        <input
            datepicker
            datepicker-format="dd/mm/yyyy"
            type="text"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:border-primary focus:ring-primary block w-full ps-10 p-2.5']) }}
        />
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
