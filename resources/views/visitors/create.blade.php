<x-app-layout>
    <x-page-card title="Novo Visitante">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('visitors.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-text-input label="Nome" name="name" :value="old('name')" placeholder="Digite o nome" required />
                <x-text-input label="Celular" name="mobile" :value="old('mobile')" placeholder="Digite o celular" />
                <x-select 
                    label="Faixa Etária" 
                    name="age_group" 
                    :options="['crianca_adolescente' => 'Criança / Adolescente', 'jovem' => 'Jovem', 'adulto' => 'Adulto', 'idoso' => 'Idoso']" 
                    :selected="old('age_group')" 
                />
                <x-select 
                    label="Gênero" 
                    name="gender" 
                    :options="['feminino' => 'Feminino', 'masculino' => 'Masculino']" 
                    :selected="old('gender')" 
                />
                <x-input-date label="Data da Visita" name="visit_date" :value="old('visit_date')" />
                <x-checkbox label="Deseja ser contactado?" name="wants_contact" :checked="old('wants_contact')" />
            </div>

            <div class="space-y-4">
                <x-textarea label="Endereço Completo" name="full_address" :value="old('full_address')" placeholder="Digite o endereço completo" />
                <x-textarea label="Observações" name="notes" :value="old('notes')" placeholder="Digite observações sobre o visitante" />
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('visitors.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit">
                        Salvar
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout> 