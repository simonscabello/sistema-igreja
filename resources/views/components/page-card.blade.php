@props(['title'])

<div class="py-4">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-6 space-y-8">
            @if(isset($title))
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="font-semibold text-xl leading-tight">
                        {{ $title }}
                    </h2>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
