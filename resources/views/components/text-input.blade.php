@props(['disabled' => false, 'label' => null])

<div>
    @if($label)
        <x-input-label :value="$label" />
    @endif
    <input @disabled($disabled) {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-medium dark:border-gray-600 focus:border-primary focus:ring-primary rounded-md shadow-sm bg-white dark:bg-gray-700 text-neutral-dark dark:text-white placeholder-gray-500 dark:placeholder-gray-400']) }}>
    <x-input-error :messages="$errors->get($attributes->get('name'))" class="mt-2" />
</div>
