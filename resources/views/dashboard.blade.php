<x-app-layout>
    <x-page-card :title="__('Painel de Controle')">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="text-neutral-dark dark:text-gray-300">
            <p class="mb-4">Bem-vindo ao sistema, {{ auth()->user()->name }}!</p>
            
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
    </x-page-card>
</x-app-layout>
