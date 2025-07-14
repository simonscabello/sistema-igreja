<x-app-layout>
    <x-page-card title="Usuário Criado com Sucesso">
        <div class="space-y-6">
            <!-- Sucesso Alert -->
            <x-alert type="success">
                <span class="font-medium">✅ Sucesso!</span> O usuário foi criado com sucesso no sistema.
            </x-alert>

            <!-- Informações do usuário criado -->
            <div class="bg-white dark:bg-gray-800 border border-neutral-medium dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-neutral-dark dark:text-gray-300 mb-4">Dados do Usuário</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Nome</label>
                        <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300">Email</label>
                        <p class="mt-1 text-sm text-neutral-medium dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                
                @if($user->roles->count() > 0)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-neutral-dark dark:text-gray-300 mb-2">Roles Atribuídos</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <span class="inline-block bg-primary text-white text-xs px-3 py-1 rounded-full">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Senha temporária -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-300 mb-4">🔐 Senha Temporária Gerada</h3>
                
                <div class="bg-white dark:bg-gray-800 border border-yellow-300 dark:border-yellow-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-1">Senha:</label>
                            <div class="flex items-center gap-3">
                                <code id="password-display" class="text-lg font-mono bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded border text-neutral-dark dark:text-gray-300">{{ $temporaryPassword }}</code>
                                <button type="button" onclick="copyPassword()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded text-sm transition-colors duration-200">
                                    📋 Copiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">⚠️ Importante:</h4>
                    <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                        <li>• Esta senha será exibida <strong>apenas uma vez</strong></li>
                        <li>• Copie e anote a senha agora antes de continuar</li>
                        <li>• O usuário <strong>deve alterar</strong> esta senha no primeiro login</li>
                        <li>• Compartilhe esta senha de forma segura com o usuário</li>
                    </ul>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex gap-4 pt-6 border-t border-neutral-medium">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                    Voltar para Lista de Usuários
                </a>
                <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-neutral-light border border-neutral-medium rounded-md font-semibold text-xs text-neutral-dark uppercase tracking-widest hover:bg-neutral-medium focus:outline-none focus:ring-2 focus:ring-neutral-medium focus:ring-offset-2 transition ease-in-out duration-150">
                    Criar Outro Usuário
                </a>
                <a href="{{ route('users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ver Usuário
                </a>
            </div>
        </div>
    </x-page-card>

    <script>
        function copyPassword() {
            const passwordElement = document.getElementById('password-display');
            const password = passwordElement.textContent.trim();
            
            // Função para copiar usando método moderno
            function copyToClipboardModern(text) {
                return navigator.clipboard.writeText(text);
            }
            
            // Função fallback para browsers mais antigos
            function copyToClipboardFallback(text) {
                return new Promise((resolve, reject) => {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    try {
                        const successful = document.execCommand('copy');
                        document.body.removeChild(textArea);
                        if (successful) {
                            resolve();
                        } else {
                            reject(new Error('Falha ao copiar'));
                        }
                    } catch (err) {
                        document.body.removeChild(textArea);
                        reject(err);
                    }
                });
            }
            
            // Tentar método moderno primeiro, depois fallback
            const copyPromise = navigator.clipboard ? 
                copyToClipboardModern(password) : 
                copyToClipboardFallback(password);
            
            copyPromise.then(() => {
                // Sucesso - usar SweetAlert
                Swal.fire({
                    title: 'Senha Copiada!',
                    text: 'A senha foi copiada para a área de transferência.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3BA99C',
                    timer: 3000,
                    timerProgressBar: true,
                    background: document.documentElement.classList.contains('dark') ? '#374151' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827'
                });
            }).catch((err) => {
                // Erro - usar SweetAlert com opção de copiar manualmente
                const isDark = document.documentElement.classList.contains('dark');
                
                Swal.fire({
                    title: 'Erro ao Copiar',
                    text: 'Não foi possível copiar automaticamente. Copie manualmente a senha abaixo:',
                    icon: 'error',
                    html: `
                        <div class="mt-4">
                            <code class="${isDark ? 'bg-gray-700 text-gray-200' : 'bg-gray-100 text-gray-800'} px-3 py-2 rounded border text-lg font-mono">${password}</code>
                        </div>
                    `,
                    confirmButtonText: 'Entendi',
                    confirmButtonColor: '#ef4444',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: isDark ? '#374151' : '#ffffff',
                    color: isDark ? '#f3f4f6' : '#111827'
                });
            });
        }
        
        // Mostrar alerta inicial sobre a importância da senha
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: '🔐 Senha Temporária Gerada',
                text: 'Esta senha será exibida apenas uma vez. Certifique-se de copiá-la agora!',
                icon: 'warning',
                confirmButtonText: 'Entendi',
                confirmButtonColor: '#3BA99C',
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: isDark ? '#374151' : '#ffffff',
                color: isDark ? '#f3f4f6' : '#111827'
            });
        });
    </script>
</x-app-layout> 