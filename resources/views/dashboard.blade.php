<x-app-layout>
    <x-page-card :title="__('Painel de Controle')">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="text-neutral-dark dark:text-gray-300">
            <h1 class="mb-4 hidden">Olá, {{ auth()->user()->name }}!</h1>

            <!-- Seção de Pessoas (Membros e Visitantes) -->
            <div class="mb-8">
               
                <div class="">
                    <!-- Aniversariantes do Mês -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                         class="w-16 h-16 rounded-full object-cover mr-3 border-2 border-primary/20">
                                    
                                    <div class="flex-1">
                                        <p class="font-medium text-primary dark:text-primary-light">{{ $aniversariante->nome }}</p>
                                        <p class="text-sm text-primary/70 dark:text-primary-light/70">{{ $aniversariante->tipo }}</p>
                                        @if($aniversariante->mobile)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $aniversariante->mobile }}</p>
                                        @endif
                                    </div>
                                    
                                    <span class="text-primary dark:text-primary-light font-semibold ml-3">{{ $aniversariante->data }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum aniversariante encontrado este mês.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Últimos Visitantes -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mt-6">
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
