// Função Alpine.js reutilizável para gerenciar categorias e subcategorias
function financialTransactionForm(initialCategory = '', initialSubcategory = '', categories = []) {
    return {
        selectedCategory: initialCategory,
        selectedSubcategory: initialSubcategory,
        subcategories: [],
        isLoadingSubcategories: false,
        categories: categories,

        init() {
            // Se já temos as categorias carregadas localmente (view create), use-as
            if (this.categories.length > 0) {
                this.updateSubcategoriesLocal();
            } else if (this.selectedCategory) {
                // Caso contrário, busque via AJAX se há categoria selecionada (view edit)
                this.fetchSubcategories();
            }
        },

        updateSubcategoriesLocal() {
            if (!this.selectedCategory) {
                this.subcategories = [];
                this.selectedSubcategory = '';
                return;
            }

            const category = this.categories.find(c => String(c.id) === String(this.selectedCategory));
            this.subcategories = category ? category.subcategories.filter(s => s.active) : [];
            
            // Verifica se a subcategoria selecionada ainda existe nas opções disponíveis
            if (this.selectedSubcategory && !this.subcategories.find(s => String(s.id) === String(this.selectedSubcategory))) {
                this.selectedSubcategory = '';
            }
        },

        async fetchSubcategories() {
            if (!this.selectedCategory) {
                this.subcategories = [];
                this.selectedSubcategory = '';
                return;
            }

            this.isLoadingSubcategories = true;

            try {
                const response = await fetch(`/categories/subcategories?category_id=${this.selectedCategory}`);
                const data = await response.json();

                this.subcategories = data;

                // Verifica se a subcategoria selecionada ainda existe nas opções disponíveis
                if (this.selectedSubcategory && !this.subcategories.find(s => String(s.id) === String(this.selectedSubcategory))) {
                    this.selectedSubcategory = '';
                }
            } catch (error) {
                console.error('Erro ao buscar subcategorias:', error);
                this.subcategories = [];
                this.selectedSubcategory = '';
            } finally {
                this.isLoadingSubcategories = false;
            }
        },

        onCategoryChange() {
            // Limpa a subcategoria selecionada ao mudar categoria
            this.selectedSubcategory = '';
            
            // Use fetch via AJAX em vez de dados locais
            this.fetchSubcategories();
        },

        get placeholderText() {
            if (this.isLoadingSubcategories) {
                return 'Carregando subcategorias...';
            }
            return 'Selecione uma subcategoria';
        },

        // Validação do formulário
        validateForm() {
            const amountInput = document.querySelector('input[name="amount"]');
            if (amountInput) {
                const amountValue = amountInput.value;
                const numericValue = getNumericValue(amountValue);
                
                if (numericValue <= 0) {
                    alert('Por favor, insira um valor válido maior que zero.');
                    amountInput.focus();
                    return false;
                }
            }
            return true;
        }
    };
}

// Função para validar formulário antes do envio
function validateTransactionForm(form) {
    const amountInput = form.querySelector('input[name="amount"]');
    if (amountInput) {
        const amountValue = amountInput.value;
        const numericValue = getNumericValue(amountValue);
        
        if (numericValue <= 0) {
            alert('Por favor, insira um valor válido maior que zero.');
            amountInput.focus();
            return false;
        }
    }
    return true;
}

// Adiciona validação ao formulário quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[action*="financial/transactions"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateTransactionForm(this)) {
                e.preventDefault();
            }
        });
    });
});
