@props(['label', 'name', 'options', 'selected' => null, 'required' => false])

<div>
    <x-input-label :value="$label" />
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'mt-1 block w-full border-neutral-medium rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50']) }}
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
