@props(['title', 'actions'])

<div class="py-4">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-5 space-y-6">

                <div class="border-b border-neutral-medium pb-4 flex items-center justify-between">
                    @if(isset($title))
                    <h2 class="font-semibold text-xl leading-tight text-neutral-dark">
                        {{ $title }}
                    </h2>
                    @endif
                    @if(isset($actions))
                        <x-link-button href="{{ $actions }}">
                          + Adicionar
                        </x-link-button>
                    @endif

                </div>


            {{ $slot }}
        </div>
    </div>
</div>
