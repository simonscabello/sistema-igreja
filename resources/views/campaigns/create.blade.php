<x-app-layout>
    <x-page-card title="Nova Campanha">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('campaigns.store') }}" method="POST">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <div>
                        <x-input-label for="name" value="Nome" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descrição" />
                        <x-textarea id="description" name="description" class="mt-1 block w-full" rows="3">{{ old('description') }}</x-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="goal_amount" value="Meta (R$)" />
                        <x-input-currency id="goal_amount" name="goal_amount" class="mt-1 block w-full" :value="old('goal_amount')" required />
                        <x-input-error :messages="$errors->get('goal_amount')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="start_date" value="Data de Início" />
                            <x-input-date id="start_date" name="start_date" class="mt-1 block w-full" :value="old('start_date')" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="end_date" value="Data de Término" />
                            <x-input-date id="end_date" name="end_date" class="mt-1 block w-full" :value="old('end_date')" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <x-select id="status" name="status" class="mt-1 block w-full" required>
                            <option value="">Selecione um status</option>
                            <option value="ativo" {{ old('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="encerrado" {{ old('status') === 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                            <option value="cancelada" {{ old('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <x-secondary-button type="button" onclick="window.history.back()">
                            Cancelar
                        </x-secondary-button>
                        <x-primary-button>
                            Criar Campanha
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout> 