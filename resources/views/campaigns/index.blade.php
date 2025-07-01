<x-app-layout>
    <x-page-card title="Campanhas Financeiras" actions="{{ route('campaigns.create') }}">
        @if(session('success'))
            <x-alert type="success" dismissible>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex justify-between items-center mb-4">
            <div class="flex-1">
                <form action="{{ route('campaigns.index') }}" method="GET" class="flex gap-2">
                    <x-text-input name="search" placeholder="Buscar campanhas..." value="{{ request('search') }}" class="flex-1 min-w-0 px-4 py-3 text-sm" />
                    <x-select label="" name="status" :options="['' => 'Todos os status', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelada' => 'Cancelada']" :selected="request('status')" class="w-44 px-4 py-3 text-sm" />
                    <div class="flex items-end">
                        <x-primary-button type="submit" class="px-4 py-3 text-sm">Buscar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-neutral-medium dark:divide-gray-700">
                <thead class="bg-neutral-light dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Meta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Progresso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-dark dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-neutral-medium dark:divide-gray-700">
                    @forelse ($campaigns as $campaign)
                        <tr class="hover:bg-neutral-light dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="max-w-xs truncate text-sm font-medium text-neutral-dark dark:text-gray-100" title="{{ $campaign->name }}">{{ $campaign->name }}</div>
                                @if($campaign->description)
                                    <div class="max-w-xs truncate text-sm text-neutral-medium dark:text-gray-400" title="{{ $campaign->description }}">{{ $campaign->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-dark whitespace-nowrap dark:text-gray-100">
                                R$ {{ number_format($campaign->goal_amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="mr-3 h-2 w-16 rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="h-2 rounded-full bg-blue-600" style="width: {{ $campaign->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-neutral-dark dark:text-gray-100">
                                        R$ {{ number_format($campaign->progress, 2, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $campaign->status === 'ativo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}{{ $campaign->status === 'encerrado' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}{{ $campaign->status === 'cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-link-button href="{{ route('campaigns.show', $campaign) }}">
                                    Ver
                                </x-link-button>
                                <x-link-button href="{{ route('campaigns.edit', $campaign) }}">
                                    Editar
                                </x-link-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-neutral-medium dark:text-gray-500">
                                Nenhuma campanha encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $campaigns->links() }}
        </div>
    </x-page-card>
</x-app-layout>
