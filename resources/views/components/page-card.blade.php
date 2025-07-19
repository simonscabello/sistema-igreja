@props(['title', 'actions'])

<div class="py-2 sm:py-4">
    <div class="w-full px-3 sm:px-4 lg:px-6 xl:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 sm:p-5 space-y-4 sm:space-y-6 transition-colors duration-300">

                <div class="border-b border-neutral-medium dark:border-gray-700 pb-3 sm:pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                    @if(isset($title))
                    <h2 class="font-semibold text-lg sm:text-xl leading-tight text-neutral-dark dark:text-white">
                        {{ $title }}
                    </h2>
                    @endif
                    @if(isset($actions))
                        <x-link-button href="{{ $actions }}" class="text-sm sm:text-base">
                          + {{ str_contains($actions, 'worship-sets') ? 'Novo Repertório' : 'Adicionar' }}
                        </x-link-button>
                    @endif

                </div>


            {{ $slot }}
        </div>
    </div>
</div>
