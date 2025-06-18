<x-app-layout>
    <x-page-card title="Transações Financeiras" actions="{{ route('financial-transactions.create') }}">
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
            <table class="min-w-full divide-y divide-neutral-medium">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-medium">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->action_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaction->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $transaction->description }}</td>
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
                            <td colspan="6" class="px-6 py-4 text-center text-neutral-medium">
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
