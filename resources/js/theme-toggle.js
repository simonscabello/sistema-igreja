// Gerenciamento de tema dark/light
document.addEventListener('DOMContentLoaded', function() {
    // Função para alternar tema
    window.toggleTheme = function() {
        const html = document.documentElement;
        const currentTheme = localStorage.getItem('theme') || 'dark';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Atualizar localStorage
        localStorage.setItem('theme', newTheme);
        
        // Atualizar classe no HTML
        if (newTheme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Atualizar ícone do botão
        updateThemeIcon(newTheme === 'dark');
    };

    // Função para atualizar ícone baseado no tema atual
    function updateThemeIcon(isDark) {
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) return;

        const icon = themeToggle.querySelector('svg');
        if (!icon) return;

        if (isDark) {
            // Mostrar ícone de sol (tema light disponível)
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            `;
        } else {
            // Mostrar ícone de lua (tema dark disponível)
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            `;
        }
    }

    // Aplicar ícone inicial baseado no tema atual
    const currentTheme = localStorage.getItem('theme') || 'dark';
    updateThemeIcon(currentTheme === 'dark');
});
