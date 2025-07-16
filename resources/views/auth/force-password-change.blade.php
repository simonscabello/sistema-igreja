<x-guest-layout>
    <!-- Logo -->
    <div class="flex justify-center mb-6">
        <x-application-logo class="w-16 h-16 fill-current text-gray-500 dark:text-gray-400" />
    </div>

    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Alterar Senha</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Por segurança, você deve alterar sua senha antes de acessar o sistema
        </p>
    </div>

    <!-- Important Notice -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">🔐 Mudança de Senha Obrigatória</h3>
        <p class="text-sm text-blue-700 dark:text-blue-300">
            Esta é sua primeira vez no sistema. Por motivos de segurança, você deve alterar a senha temporária fornecida pelo administrador antes de acessar o painel.
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('password.force-change.update') }}">
        @csrf

        <!-- New Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Nova Senha')" required="true" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          placeholder="Digite sua nova senha"
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Mínimo de 8 caracteres. Use uma senha forte e segura.
            </p>
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" required="true" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          placeholder="Confirme sua nova senha"
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Security Tips -->
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <h4 class="text-sm font-medium text-green-800 dark:text-green-300 mb-2">💡 Dicas para uma senha segura:</h4>
            <ul class="text-sm text-green-700 dark:text-green-300 space-y-1">
                <li>• Use pelo menos 8 caracteres</li>
                <li>• Combine letras maiúsculas e minúsculas</li>
                <li>• Inclua números e símbolos especiais</li>
                <li>• Evite informações pessoais óbvias</li>
            </ul>
        </div>

        <div class="flex items-center justify-end">
            <!-- Submit Button -->
            <x-primary-button type="submit">
                {{ __('Alterar Senha') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Logout Form (separate from main form) -->
    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit"
                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                Sair do Sistema
            </button>
        </form>
    </div>

    <!-- Additional Info -->
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Após alterar sua senha, você será redirecionado automaticamente para o painel administrativo.
        </p>
    </div>
</x-guest-layout>
