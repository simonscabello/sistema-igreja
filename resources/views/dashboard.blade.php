<x-app-layout>
    <x-page-card :title="__('Painel de Controle')">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="text-neutral-dark dark:text-gray-300">
            <h1 class="mb-4 hidden">Olá, {{ auth()->user()->name }}!</h1>

            <!-- Seção de Estatísticas/Resumos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Card de Membros -->
                @can('visualizar_membros')
                    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Membros</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalMembros) }}</p>
                            </div>
                        </div>
                    </div>
                @endcan

                <!-- Card de Visitantes -->
                @can('visualizar_visitantes')
                    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Visitantes</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalVisitantes) }}</p>
                            </div>
                        </div>
                    </div>
                @endcan

                <!-- Card de Departamentos -->
                @can('visualizar_departamentos')
                    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Departamentos</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDepartamentos) }}</p>
                            </div>
                        </div>
                    </div>
                @endcan


            </div>

            <!-- Seção de Pessoas (Membros e Visitantes) -->
            <div class="mb-6 sm:mb-8">

                <div class="">
                    <!-- Aniversariantes do Mês -->
                    <div class="bg-white dark:bg-gray-800 py-4 sm:py-6 rounded-lg shadow">
                        <h3 class="text-base sm:text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                            Aniversariantes do Mês
                        </h3>
                        <div class="space-y-2">
                            @forelse($aniversariantesDoMes as $aniversariante)
                                <div class="relative flex items-center p-3
                                    {{ $aniversariante->is_today ? 'bg-primary/10 dark:bg-primary/10' : 'bg-primary/5 dark:bg-primary/5' }}
                                    border
                                    {{ $aniversariante->is_today ? 'border-primary/30 dark:border-primary/30' : 'border-primary/10 dark:border-primary/20' }}
                                    rounded">

                                    @if($aniversariante->is_today)
                                        <div class="absolute -top-1 -right-1 bg-primary text-white text-xs px-2 py-1 rounded-full font-semibold">
                                            Hoje!
                                        </div>
                                    @endif

                                    <img src="{{ $aniversariante->foto_url }}"
                                         alt="Foto de {{ $aniversariante->nome }}"
                                         class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover mr-3 border-2 border-primary/20 flex-shrink-0">

                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-primary dark:text-primary-light truncate">{{ $aniversariante->nome }}</p>
                                        <p class="text-xs sm:text-sm text-primary/70 dark:text-primary-light/70">{{ $aniversariante->tipo }}</p>
                                        @if($aniversariante->mobile)
                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hidden sm:block">{{ $aniversariante->mobile }}</p>
                                        @endif
                                    </div>

                                    <span class="text-primary dark:text-primary-light font-semibold ml-3 text-sm sm:text-base flex-shrink-0">{{ $aniversariante->data }}</span>
                                </div>
                            @empty
                                <div class="text-center py-6 sm:py-8">
                                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nenhum aniversariante</h3>
                                    <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">Não há aniversários este mês.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Últimos Visitantes -->
                    <div class="bg-white dark:bg-gray-800 py-4 sm:py-6 rounded-lg shadow mt-6">
                        <h3 class="text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Últimos Visitantes
                        </h3>
                        <div class="space-y-2">
                            @forelse($ultimosVisitantes as $visitante)
                                <div class="flex items-center justify-between p-3 bg-primary/5 dark:bg-primary/5 border border-primary/10 dark:border-primary/20 rounded">
                                    <div class="flex-1">
                                        <p class="font-medium text-primary dark:text-primary-light">{{ $visitante->nome }}</p>
                                        @if($visitante->mobile)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $visitante->mobile }}</p>
                                        @endif
                                        <p class="text-sm text-primary/70 dark:text-primary-light/70 mt-1">
                                            Primeira visita: {{ $visitante->primeira_visita }}
                                        </p>
                                    </div>
                                    <a href="{{ route('visitors.show', $visitante->id) }}"
                                       class="text-sm bg-primary text-white px-3 py-1 rounded hover:bg-primary-dark transition-colors duration-150">
                                        Visualizar
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Este mês ainda não recebemos visitantes.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Acesso Rápido -->
            <div class="hidden">
                <h2 class="text-2xl font-bold mb-6 text-neutral-dark dark:text-white">Acesso Rápido</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @can('gerenciar_usuarios')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Usuários do Sistema</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Gerenciar usuários e suas permissões</p>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                Acessar
                            </a>
                        </div>
                    @endcan

                    @can('visualizar_membros')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Membros</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Gerenciar membros da igreja</p>
                            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                Acessar
                            </a>
                        </div>
                    @endcan

                    @can('visualizar_visitantes')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Visitantes</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Gerenciar visitantes da igreja</p>
                            <a href="{{ route('visitors.index') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                Acessar
                            </a>
                        </div>
                    @endcan

                    @can('visualizar_financeiro')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-2">Financeiro</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Gerenciar transações financeiras</p>
                            <a href="{{ route('financial-transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                Acessar
                            </a>
                        </div>
                    @endcan
                </div>
            </div>

        </div>
    </x-page-card>
</x-app-layout>
