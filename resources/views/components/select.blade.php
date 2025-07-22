@props([
    'options' => [],
    'selected' => [],
    'name',
    'multiple' => false,
    'imageField' => null,
    'maxVisible' => 5,
    'placeholder' => 'Selecione...',
    'label' => null,
    'required' => false,
    'error' => null
])

@php
    // Converter selected para array se não for
    if (!is_array($selected)) {
        $selected = $selected ? [$selected] : [];
    }
    
    // Preparar opções para JSON
    $optionsForJson = [];
    foreach ($options as $id => $option) {
        if (is_array($option)) {
            $optionsForJson[] = [
                'id' => $id,
                'name' => $option['name'] ?? $option['label'] ?? $option,
                'image' => $imageField ? ($option[$imageField] ?? null) : null
            ];
        } else {
            $optionsForJson[] = [
                'id' => $id,
                'name' => $option,
                'image' => null
            ];
        }
    }
@endphp

<div>
    @if($label)
        <x-input-label :value="$label" :required="$required" />
    @endif
    
    <div 
        data-advanced-select
        data-options="{{ json_encode($optionsForJson) }}"
        data-selected="{{ json_encode($selected) }}"
        data-multiple="{{ $multiple ? 'true' : 'false' }}"
        data-image-field="{{ $imageField }}"
        data-max-visible="{{ $maxVisible }}"
        data-placeholder="{{ $placeholder }}"
        data-name="{{ $name }}"
        class="mt-1"
    >
        <!-- Elemento placeholder que será substituído pelo JavaScript -->
        <div class="w-full px-3 py-2 text-sm border border-neutral-medium dark:border-gray-600 focus:border-primary focus:ring-primary rounded-md shadow-sm bg-white dark:bg-gray-700 text-neutral-dark dark:text-white placeholder-gray-500">
            {{ $placeholder }}
        </div>
    </div>
    
    @if($error)
        <x-input-error :messages="$error" class="mt-2" />
    @endif
</div> 