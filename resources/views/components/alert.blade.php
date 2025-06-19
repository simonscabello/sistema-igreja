@props(['type' => 'success', 'dismissible' => false])

@php
    $classes = [
        'success' => 'text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400',
        'warning' => 'text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400',
        'error' => 'text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400',
        'info' => 'text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400'
    ];

    $icons = [
        'success' => '<path d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" />',
        'warning' => '<path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />',
        'error' => '<path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />',
        'info' => '<path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />'
    ];

    $buttonClasses = [
        'success' => 'bg-green-50 text-green-500 focus:ring-green-400 hover:bg-green-200 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700',
        'warning' => 'bg-yellow-50 text-yellow-500 focus:ring-yellow-400 hover:bg-yellow-200 dark:bg-gray-800 dark:text-yellow-400 dark:hover:bg-gray-700',
        'error' => 'bg-red-50 text-red-500 focus:ring-red-400 hover:bg-red-200 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700',
        'info' => 'bg-blue-50 text-blue-500 focus:ring-blue-400 hover:bg-blue-200 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700'
    ];
@endphp

<div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-sm rounded-lg {{ $classes[$type] }}" role="alert">
    <svg class="flex-shrink-0 inline w-4 h-4 me-3" fill="currentColor" viewBox="0 0 20 20">
        {!! $icons[$type] !!}
    </svg>
    <span class="sr-only">{{ ucfirst($type) }}</span>
    <div class="flex-1">
        {{ $slot }}
    </div>
    @if($dismissible)
        <button @click="show = false" type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8 {{ $buttonClasses[$type] }}" aria-label="Fechar">
            <span class="sr-only">Fechar</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    @endif
</div>
