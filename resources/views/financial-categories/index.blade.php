<x-app-layout>
    <x-page-card title="Categorias Financeiras" actions="{{ route('financial-categories.create') }}">
        <div class="flex justify-between items-center mb-4">
            <div class="flex-1">
                <form action="{{ route('financial-categories.index') }}" method="GET" class="flex gap-2">
                    <x-text-input  name="search" placeholder="Buscar categorias..." value="{{ request('search') }}" />
                    <div class="flex items-end">
                        <x-primary-button type="submit" class="px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-medium">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-medium">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                            <td class="px-6 py-4">{{ $category->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('financial-categories.edit', $category) }}">
                                    Editar
                                </x-link-button>
                                <form action="{{ route('financial-categories.destroy', $category) }}" method="POST" class="inline-block">
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
                            <td colspan="4" class="px-6 py-4 text-center text-neutral-medium">
                                Nenhuma categoria encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </x-page-card>
</x-app-layout>
