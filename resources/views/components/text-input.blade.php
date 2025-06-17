@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-medium focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
