@props(['label', 'name', 'options', 'selected' => null, 'required' => false])

<div>
    <x-input-label :value="$label" />
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm
        focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white']) }}
    >
        <option value="">Selecione...</option>
        @foreach($options as $option)
            <option value="{{ $option }}" {{ $selected == $option ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
