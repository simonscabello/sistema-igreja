<!-- Sidebar Desktop -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-neutral-medium sm:flex hidden flex-col">
    <!-- Topo alinhado com topbar -->
    <div class="flex items-center justify-center h-16 px-4 bg-neutral-light border-b border-neutral-medium gap-2">
        <x-application-logo class="w-8 h-8" />
        <h1 class="text-xl font-semibold text-neutral-dark">{{ config('app.name') }}</h1>
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </x-slot>
            {{ __('Painel') }}
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </x-slot>
            {{ __('Perfil') }}
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('members.index') }}" :active="request()->routeIs('members.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot>
            {{ __('Membros') }}
        </x-sidebar-link>

        <x-sidebar-dropdown title="Finanças" icon="dollar-sign" :active="request()->routeIs('financial-categories.*') || request()->routeIs('financial-transactions.*')">
            <x-sidebar-link href="{{ route('financial-categories.index') }}" :active="request()->routeIs('financial-categories.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </x-slot>
                {{ __('Categorias') }}
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('financial-transactions.index') }}" :active="request()->routeIs('financial-transactions.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot>
                {{ __('Transações') }}
            </x-sidebar-link>
        </x-sidebar-dropdown>
    </nav>
</div>

<!-- Sidebar Mobile -->
<div x-show="sidebarOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-neutral-medium sm:hidden transform transition-transform duration-300 ease-in-out"
     @click.away="sidebarOpen = false"
     style="display: none;">

    <!-- Topo alinhado com topbar -->
    <div class="flex items-center justify-center h-16 px-4 bg-neutral-light border-b border-neutral-medium gap-2">
        <x-application-logo class="w-8 h-8" />
        <h1 class="text-xl font-semibold text-neutral-dark">{{ config('app.name') }}</h1>
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </x-slot>
            {{ __('Painel') }}
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </x-slot>
            {{ __('Perfil') }}
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('members.index') }}" :active="request()->routeIs('members.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot>
            {{ __('Membros') }}
        </x-sidebar-link>

        <x-sidebar-dropdown title="Finanças" icon="dollar-sign" :active="request()->routeIs('financial-categories.*') || request()->routeIs('financial-transactions.*')">
            <x-sidebar-link href="{{ route('financial-categories.index') }}" :active="request()->routeIs('financial-categories.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </x-slot>
                {{ __('Categorias') }}
            </x-sidebar-link>
            <x-sidebar-link href="{{ route('financial-transactions.index') }}" :active="request()->routeIs('financial-transactions.*')">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot>
                {{ __('Transações') }}
            </x-sidebar-link>
        </x-sidebar-dropdown>
    </nav>
</div>

<!-- Overlay Mobile -->
<div class="fixed inset-0 z-40 bg-neutral-dark bg-opacity-75 sm:hidden"
     x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     style="display: none;">
</div>
