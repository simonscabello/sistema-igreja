@props(['disabled' => false, 'label' => null])

<div>
    @if($label)
        <x-input-label :value="$label" />
    @endif
    <input @disabled($disabled) {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-medium focus:border-primary focus:ring-primary rounded-md shadow-sm']) }}>
    <x-input-error :messages="$errors->get($attributes->get('name'))" class="mt-2" />
</div>
