<x-app-layout>
    <x-page-card title="Detalhes do Membro">
        <!-- Container principal com foto à esquerda e informações fluindo ao redor -->
        <div class="flex flex-wrap gap-6">
            <!-- Seção da Foto - fixa à esquerda -->
            <div class="flex-shrink-0">
                <div class="flex flex-col items-center">
                    <x-avatar :member="$member" size="w-40 h-40" />
                    <h2 class="text-xl font-semibold text-neutral-dark dark:text-gray-300 mt-4 text-center">{{ $member->full_name }}</h2>
                </div>
            </div>

            <!-- Informações que fluem ao redor da foto -->
            <div class="flex-1 min-w-0">
                <!-- Informações Pessoais -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Nome Completo</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->full_name }}</dd>
                        </div>
                        @if($member->email)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">E-mail</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->email }}</dd>
                            </div>
                        @endif
                        @if($member->mobile)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Celular</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->mobile }}</dd>
                            </div>
                        @endif
                        @if($member->phone)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Telefone</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->phone }}</dd>
                            </div>
                        @endif
                        @if($member->gender)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Gênero</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ ucfirst($member->gender) }}</dd>
                            </div>
                        @endif
                        @if($member->marital_status)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Estado Civil</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ ucfirst($member->marital_status) }}</dd>
                            </div>
                        @endif
                    </div>
                    <!-- Datas em linhas separadas -->
                    <div class="space-y-2 mt-4">
                        @if($member->birth_date)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Data de Nascimento</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->birth_date->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                        @if($member->baptism_date)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Data do Batismo</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->baptism_date->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                        @if($member->admission_date)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Data de Admissão</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->admission_date->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                        @if($member->wedding_date)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Data do Casamento</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->wedding_date->format('d/m/Y') }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Endereço - ocupa toda a largura -->
        @if($member->street || $member->neighborhood || $member->city || $member->state)
            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Endereço</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($member->street)
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Rua</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">
                                {{ $member->street }}{{ $member->number ? ', ' . $member->number : '' }}
                                @if($member->complement)
                                    <br><span class="text-xs text-neutral-medium">{{ $member->complement }}</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    @if($member->neighborhood)
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Bairro</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->neighborhood }}</dd>
                        </div>
                    @endif
                    @if($member->city || $member->state)
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Cidade/Estado</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">
                                {{ $member->city }}{{ $member->state ? ' - ' . $member->state : '' }}
                            </dd>
                        </div>
                    @endif
                    @if($member->zip_code)
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">CEP</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $member->zip_code }}</dd>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="border-t border-neutral-medium pt-6">
            <div class="flex gap-4">
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                    Voltar
                </a>
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
            </div>
        </div>
    </x-page-card>
</x-app-layout> 