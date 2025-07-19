<x-app-layout>
    <x-page-card title="Novo Membro">
        @if($errors->any())
            <x-alert type="error" dismissible>
                <span class="font-medium">Erro!</span> Por favor, corrija os erros abaixo.
            </x-alert>
        @endif

        <form action="{{ route('members.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data" x-data="{ photoPreview: null }">
            @csrf

            <!-- Linha da foto e texto auxiliar -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 w-full mb-2">
                <div class="relative mx-auto sm:mx-0">
                    <div id="foto-preview">
                        <template x-if="!photoPreview">
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-2 border-primary bg-gray-100 dark:bg-gray-600 flex items-center justify-center relative overflow-hidden">
                                <svg class="absolute w-16 h-16 sm:w-24 sm:h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            </div>
                        </template>
                        <template x-if="photoPreview">
                            <img :src="photoPreview" alt="Preview da foto" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-2 border-primary">
                        </template>
                    </div>
                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" class="hidden" @change="photoPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null" />
                    <label for="foto_perfil" class="absolute inset-0 flex items-center justify-center cursor-pointer bg-black bg-opacity-10 rounded-full hover:bg-opacity-20 transition-all">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white opacity-0 hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                </div>
                <div class="text-center sm:text-left">
                    <span class="text-xs sm:text-sm text-neutral-medium">Clique para selecionar uma foto<br>(jpg, jpeg, png, até 5MB)</span>
                    @error('foto_perfil')
                        <span class="block text-xs sm:text-sm text-red-500 mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <hr class="border-neutral-medium mb-6">

            <!-- Primeira linha de campos ao lado da foto -->
            <div class="flex-1 min-w-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-text-input label="Nome Completo" name="full_name" :value="old('full_name')" placeholder="Digite o nome completo" required="true" />
                    <x-text-input label="Email" name="email" type="email" :value="old('email')" placeholder="Digite o email" />
                    <x-text-input label="Celular" name="mobile" :value="old('mobile')" placeholder="Digite o celular" required="true" />
                    <x-text-input label="Telefone" name="phone" :value="old('phone')" placeholder="Digite o telefone" />
                </div>
            </div>

            <!-- Restante dos campos embaixo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-select label="Gênero" name="gender" :options="['Masculino' => 'Masculino', 'Feminino' => 'Feminino', 'Outro' => 'Outro']" :selected="old('gender')" required="true" />
                <x-select label="Estado Civil" name="marital_status" :options="['Solteiro' => 'Solteiro', 'Casado' => 'Casado', 'Divorciado' => 'Divorciado', 'Viúvo' => 'Viúvo']" :selected="old('marital_status')" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-input-date label="Data de Nascimento" name="birth_date" :value="old('birth_date')" required="true" />
                <x-input-date label="Data de Batismo" name="baptism_date" :value="old('baptism_date')" />
                <x-input-date label="Data de Admissão" name="admission_date" :value="old('admission_date')" />
                <x-input-date label="Data de Casamento" name="wedding_date" :value="old('wedding_date')" />
            </div>

            <!-- Endereço -->
            <div class="border-t border-neutral-medium pt-6">
                <h3 class="text-base sm:text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Endereço</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-text-input label="CEP" name="zip_code" id="zip_code" :value="old('zip_code')" placeholder="Digite o CEP" required="true" class="sm:col-span-1" />
                    <x-text-input label="Rua" name="street" id="street" :value="old('street')" placeholder="Digite a rua" class="sm:col-span-2" />
                    <x-text-input label="Bairro" name="neighborhood" id="neighborhood" :value="old('neighborhood')" placeholder="Digite o bairro" />
                    <x-text-input label="Cidade" name="city" id="city" :value="old('city')" placeholder="Digite a cidade" />
                    <x-text-input label="Estado" name="state" id="state" maxlength="2" :value="old('state')" placeholder="UF" />
                    <x-text-input label="Número" name="number" :value="old('number')" placeholder="Digite o número" />
                    <x-text-input label="Complemento" name="complement" :value="old('complement')" placeholder="Digite o complemento" class="sm:col-span-2" />
                </div>
            </div>

            <div class="border-t border-neutral-medium mt-6 pt-6">
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('members.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <x-primary-button type="submit" class="w-full sm:w-auto">
                        Salvar
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-page-card>
</x-app-layout>

