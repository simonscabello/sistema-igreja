@props(['title'])

<div class="py-4">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-5 space-y-6">
            @if(isset($title))
                <div class="border-b border-neutral-medium pb-4">
                    <h2 class="font-semibold text-xl leading-tight text-neutral-dark">
                        {{ $title }}
                    </h2>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
