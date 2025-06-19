document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const form = button.closest('form');
            const isDarkMode = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Você tem certeza?',
                text: "Essa ação não poderá ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3BA99C',
                cancelButtonColor: '#F44336',
                background: isDarkMode ? '#1f2937' : '#ffffff',
                color: isDarkMode ? '#f3f4f6' : '#374151',
                backdrop: isDarkMode ? 'rgba(0, 0, 0, 0.8)' : 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: isDarkMode ? 'dark-swal-popup' : '',
                    title: isDarkMode ? 'dark-swal-title' : '',
                    content: isDarkMode ? 'dark-swal-content' : '',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : '',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : ''
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});