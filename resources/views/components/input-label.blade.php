@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-md text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
