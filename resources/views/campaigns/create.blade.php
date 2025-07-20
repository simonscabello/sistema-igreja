<x-app-layout>
    <x-page-card title="Nova Campanha">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('financial.campaigns.store') }}" method="POST">
            @csrf

            <div class="max-w-2xl">
                <div class="space-y-6">
                    <div>
                        <x-input-label for="name" value="Nome" required="true" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-textarea name="description" label="Descrição" class="mt-1 block w-full" rows="3">{{ old('description') }}</x-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-currency name="goal_amount" label="Meta (R$)" class="mt-1 block w-full" :value="old('goal_amount')" required="true" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-date name="start_date" label="Data de Início" class="mt-1 block w-full" :value="old('start_date')" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-date name="end_date" label="Data de Término" class="mt-1 block w-full" :value="old('end_date')" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-select name="status" label="Status" :options="['' => 'Selecione um status', 'ativo' => 'Ativo', 'encerrado' => 'Encerrado', 'cancelada' => 'Cancelada']" :selected="old('status')" class="mt-1 block w-full" required="true" />
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