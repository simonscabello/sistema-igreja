<x-app-layout>
    <x-page-card title="Novo Membro">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('members.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-text-input label="Nome Completo" name="full_name" placeholder="Digite o nome completo" required />
                <x-text-input label="Email" name="email" type="email" placeholder="Digite o email" />
                <x-text-input label="Telefone" name="phone" placeholder="Digite o telefone" />
                <x-text-input label="Celular" name="mobile" placeholder="Digite o celular" required />
                <x-select label="Gênero" name="gender" :options="['Masculino', 'Feminino', 'Outro']" required />
                <x-select label="Estado Civil" name="marital_status" :options="['Solteiro', 'Casado', 'Divorciado', 'Viúvo']" />
                <x-input-date label="Data de Nascimento" name="birth_date" required />
                <x-input-date label="Data de Batismo" name="baptism_date" />
                <x-input-date label="Data de Admissão" name="admission_date" />
                <x-input-date label="Data de Casamento" name="wedding_date" />
            </div>

            <div class="border-t border-neutral-medium pt-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-text-input label="CEP" name="zip_code" id="zip_code" placeholder="Digite o CEP" required />
                    <x-text-input label="Rua" name="street" id="street" placeholder="Digite a rua" />
                    <x-text-input label="Bairro" name="neighborhood" id="neighborhood" placeholder="Digite o bairro" />
                    <x-text-input label="Cidade" name="city" id="city" placeholder="Digite a cidade" />
                    <x-text-input label="Estado" name="state" id="state" maxlength="2" placeholder="UF" />
                    <x-text-input label="Número" name="number" placeholder="Digite o número" />
                    <x-text-input label="Complemento" name="complement" placeholder="Digite o complemento" />
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex gap-4">
                    <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
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

