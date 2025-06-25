@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-bold text-md text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>
