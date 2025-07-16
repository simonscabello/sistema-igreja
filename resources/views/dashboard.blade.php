<x-app-layout>
    <x-page-card :title="__('Painel de Controle')">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="text-neutral-dark dark:text-gray-300">
            <p class="mb-4">Bem-vindo ao sistema, {{ auth()->user()->name }}!</p>

            <!-- Seção de Pessoas (Membros e Visitantes) -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-6 text-neutral-dark dark:text-white">Seção de Pessoas</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Distribuição de Membros por Gênero -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Distribuição por Gênero
                        </h3>
                        <div class="space-y-3">
                            @forelse($distribuicaoGenero as $item)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="font-medium">{{ $item->genero }}</span>
                                    <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $item->total }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum membro cadastrado</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Perfil Etário dos Membros -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Perfil Etário
                        </h3>
                        <div class="space-y-3">
                            @forelse($perfilEtario as $item)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <span class="font-medium">{{ $item->faixa_etaria }} anos</span>
                                    <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $item->total }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum membro com idade informada</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Aniversariantes do Mês -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.5 1.5 0 013 16.5V19a3 3 0 003 3h12a3 3 0 003-3v-2.454z"></path>
                            </svg>
                            Aniversariantes do Mês
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($aniversariantesDoMes as $aniversariante)
                                <div class="flex justify-between items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded">
                                    <div>
                                        <p class="font-medium text-yellow-800 dark:text-yellow-200">{{ $aniversariante->nome }}</p>
                                        <p class="text-sm text-yellow-600 dark:text-yellow-300">{{ $aniversariante->tipo }}</p>
                                    </div>
                                    <span class="text-yellow-800 dark:text-yellow-200 font-semibold">{{ $aniversariante->data }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum aniversariante este mês</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Últimos Visitantes -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4 text-neutral-dark dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Últimos Visitantes
                        </h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($ultimosVisitantes as $visitante)
                                <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded">
                                    <p class="font-medium text-green-800 dark:text-green-200">{{ $visitante->nome }}</p>
                                    <div class="text-sm text-green-600 dark:text-green-300 mt-1">
                                        <p>Primeira visita: {{ $visitante->primeira_visita }}</p>
                                        <p>Última visita: {{ $visitante->ultima_visita }}</p>
                                        <p>Total de visitas: {{ $visitante->quantidade_visitas }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum visitante registrado</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Acesso Rápido -->
            <div>
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
