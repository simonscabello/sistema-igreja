<x-app-layout>
    <x-page-card title="Campanhas Financeiras">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 flex-1">
                <form method="GET" class="flex gap-2">
                    <x-text-input
                        type="text"
                        name="search"
                        placeholder="Buscar campanhas..."
                        value="{{ request('search') }}"
                        class="w-full sm:w-64"
                    />
                    <x-primary-button type="submit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </x-primary-button>
                </form>

                <select name="status" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Todos os status</option>
                    <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="encerrado" {{ request('status') === 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                    <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <x-primary-button href="{{ route('campaigns.create') }}" class="whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Campanha
            </x-primary-button>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Meta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progresso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($campaigns as $campaign)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $campaign->name }}</div>
                                    @if($campaign->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $campaign->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    R$ {{ number_format($campaign->goal_amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $campaign->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            R$ {{ number_format($campaign->progress, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @if($campaign->start_date && $campaign->end_date)
                                        {{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}
                                    @elseif($campaign->start_date)
                                        A partir de {{ $campaign->start_date->format('d/m/Y') }}
                                    @elseif($campaign->end_date)
                                        Até {{ $campaign->end_date->format('d/m/Y') }}
                                    @else
                                        Sem período definido
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $campaign->status === 'ativo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}{{ $campaign->status === 'encerrado' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}{{ $campaign->status === 'cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <x-link-button href="{{ route('campaigns.show', $campaign) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </x-link-button>
                                        <x-link-button href="{{ route('campaigns.edit', $campaign) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </x-link-button>
                                        <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta campanha?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Nenhuma campanha encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $campaigns->links() }}
        </div>
    </x-page-card>
</x-app-layout> 