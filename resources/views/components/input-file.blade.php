@props([
    'label' => null,
    'name',
    'required' => false,
    'accept' => null,
    'multiple' => false,
    'helpText' => null,
    'maxSize' => '10MB',
    'allowedTypes' => 'PDF, JPG, PNG',
])

<div class="space-y-2">
    @if($label)
        <x-input-label :value="$label" :required="$required" />
    @endif
    
    <div class="relative">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            @if($accept) accept="{{ $accept }}" @endif
            @if($multiple) multiple @endif
            {{ $attributes->merge(['class' => 'block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark file:cursor-pointer']) }}
        />
    </div>
    
    @if($helpText)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Formatos aceitos: {{ $allowedTypes }} | Tamanho máximo: {{ $maxSize }}
        </p>
    @endif
    
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
    @if($multiple)
        <x-input-error :messages="$errors->get($name . '.*')" class="mt-2" />
    @endif
</div> 