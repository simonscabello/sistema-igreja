<x-app-layout>
    <x-page-card title="Detalhes do Visitante">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações Pessoais</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Nome</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $visitor->name }}</dd>
                        </div>
                        @if($visitor->mobile)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Celular</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $visitor->mobile }}</dd>
                            </div>
                        @endif
                        @if($visitor->age_group)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Faixa Etária</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $visitor->age_group === 'crianca_adolescente' ? 'Criança / Adolescente' : ucfirst($visitor->age_group) }}</dd>
                            </div>
                        @endif
                        @if($visitor->gender)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Gênero</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ ucfirst($visitor->gender) }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações da Visita</h3>
                    <dl class="space-y-3">
                        @if($visitor->visit_date)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Data da Visita</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $visitor->visit_date->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Deseja ser contactado?</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">
                                @if($visitor->wants_contact)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Sim
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                        Não
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($visitor->full_address)
                <div class="border-t border-neutral-medium pt-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Endereço</h3>
                    <p class="text-sm text-neutral-dark dark:text-gray-300 whitespace-pre-line">{{ $visitor->full_address }}</p>
                </div>
            @endif

            @if($visitor->notes)
                <div class="border-t border-neutral-medium pt-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Observações</h3>
                    <p class="text-sm text-neutral-dark dark:text-gray-300 whitespace-pre-line">{{ $visitor->notes }}</p>
                </div>
            @endif

            <div class="border-t border-neutral-medium pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('visitors.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Voltar
                    </a>
                    <x-link-button href="{{ route('visitors.edit', $visitor) }}">
                        Editar
                    </x-link-button>
                    <form action="{{ route('visitors.destroy', $visitor) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit">
                            Excluir
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </x-page-card>
</x-app-layout> 