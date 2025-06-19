<x-app-layout>
    <x-page-card title="Transações Financeiras" actions="{{ route('financial-transactions.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="mb-4">
            <form action="{{ route('financial-transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 items-end">
                <div>
                    <x-input-label value="Busca" />
                    <x-text-input name="search" placeholder="Buscar transações..." value="{{ request('search') }}" />
                </div>
                <x-select label="Tipo" name="type" :options="['' => 'Todos os tipos', 'entrada' => 'Entradas', 'saida' => 'Saídas']" :selected="request('type')" />
                <x-select label="Categoria" name="category" :options="$categories->pluck('name', 'id')->prepend('Todas as categorias', '')" :selected="request('category')" />
                <div class="flex items-end">
                    <x-primary-button type="submit" class="px-4 py-3 text-sm">Buscar</x-primary-button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $transaction->action_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $transaction->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $transaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-neutral-dark dark:text-gray-300">{{ $transaction->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('financial-transactions.edit', $transaction) }}">
                                    Editar
                                </x-link-button>
                                <form action="{{ route('financial-transactions.destroy', $transaction) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit">
                                        Excluir
                                    </x-danger-button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhuma transação encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </x-page-card>
</x-app-layout>
