<x-app-layout>
    <x-page-card title="Detalhes do Departamento">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Informações Gerais</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Título</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $departamento->title }}</dd>
                        </div>
                        @if($departamento->description)
                            <div>
                                <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Descrição</dt>
                                <dd class="text-sm text-neutral-dark dark:text-gray-300">{{ $departamento->description }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Status</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">
                                @if($departamento->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Inativo
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-neutral-medium dark:text-gray-400">Total de Membros</dt>
                            <dd class="text-sm text-neutral-dark dark:text-gray-300">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $departamento->members_count }} membros
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($departamento->responsibleMembers->count() > 0)
                <div class="border-t border-neutral-medium pt-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Líderes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($departamento->responsibleMembers as $member)
                            <div class="bg-neutral-light dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-neutral-dark dark:text-gray-300">{{ $member->full_name }}</h4>
                                @if($member->email)
                                    <p class="text-sm text-neutral-medium dark:text-gray-400">{{ $member->email }}</p>
                                @endif
                                @if($member->mobile)
                                    <p class="text-sm text-neutral-medium dark:text-gray-400">{{ $member->mobile }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($departamento->members->count() > 0)
                <div class="border-t border-neutral-medium pt-6">
                    <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Membros</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($departamento->members as $member)
                            <div class="bg-neutral-light dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-neutral-dark dark:text-gray-300">{{ $member->full_name }}</h4>
                                @if($member->email)
                                    <p class="text-sm text-neutral-medium dark:text-gray-400">{{ $member->email }}</p>
                                @endif
                                @if($member->mobile)
                                    <p class="text-sm text-neutral-medium dark:text-gray-400">{{ $member->mobile }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="border-t border-neutral-medium pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('departments.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Voltar
                    </a>
                    <x-link-button href="{{ route('departments.edit', $departamento) }}">
                        Editar
                    </x-link-button>
                    <form action="{{ route('departments.destroy', $departamento) }}" method="POST" class="inline-block">
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
