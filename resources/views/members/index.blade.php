<x-app-layout>
    <x-page-card title="Membros" actions="{{ route('members.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex justify-between items-center mb-4">
            <div class="flex-1">
                <form action="{{ route('members.index') }}" method="GET" class="flex gap-2">
                    <x-text-input name="search" placeholder="Buscar membros..." value="{{ request('search') }}" />
                    <x-primary-button type="submit">Buscar</x-primary-button>
                </form>
            </div>

        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-medium">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Telefone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark uppercase tracking-wider">Cidade</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-medium">
                    @forelse($members as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->full_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->mobile }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->city }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('members.edit', $member) }}">
                                    Editar
                                </x-link-button>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block">
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
                            <td colspan="5" class="px-6 py-4 text-center text-neutral-medium">
                                Nenhum membro encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $members->links() }}
        </div>
    </x-page-card>
</x-app-layout>
