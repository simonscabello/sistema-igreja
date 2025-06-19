<x-app-layout>
    <x-page-card title="Subcategorias Financeiras" actions="{{ route('subcategories.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex justify-between items-center mb-4">
            <div class="flex-1">
                <form action="{{ route('subcategories.index') }}" method="GET" class="flex gap-2">
                    <x-text-input name="search" placeholder="Buscar subcategorias..." value="{{ request('search') }}" />
                    <div class="flex items-end">
                        <x-primary-button type="submit" class="px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse ($subcategories as $subcategory)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-neutral-dark dark:text-gray-300">{{ $subcategory->name }}</td>
                            <td class="px-6 py-4 text-neutral-dark dark:text-gray-300">{{ $subcategory->financialCategory->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subcategory->active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                    {{ $subcategory->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('subcategories.edit', $subcategory) }}">
                                    Editar
                                </x-link-button>
                                <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" class="inline-block">
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
                            <td colspan="4" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhuma subcategoria encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $subcategories->links() }}
        </div>
    </x-page-card>
</x-app-layout>
