@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-md text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
