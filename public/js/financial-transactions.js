// Função Alpine.js reutilizável para gerenciar categorias e subcategorias
function financialTransactionForm(initialCategory = '', initialSubcategory = '', categories = []) {
    return {
        selectedCategory: initialCategory,
        selectedSubcategory: '',
        targetSubcategory: initialSubcategory, // Subcategoria que deve ser selecionada após carregamento
        subcategories: [],
        isLoadingSubcategories: false,
        categories: categories,

        init() {
            // Se já temos as categorias carregadas localmente (view create), use-as
            if (this.categories.length > 0) {
                this.updateSubcategoriesLocal();
                if (this.targetSubcategory) {
                    this.selectedSubcategory = this.targetSubcategory;
                }
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
            this.subcategories = category ? category.subcategories : [];
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

                // Após carregar as subcategorias, tente selecionar a subcategoria alvo
                if (this.targetSubcategory && this.subcategories.find(s => String(s.id) === String(this.targetSubcategory))) {
                    this.selectedSubcategory = this.targetSubcategory;
                } else if (this.selectedSubcategory && !this.subcategories.find(s => String(s.id) === String(this.selectedSubcategory))) {
                    // Se a subcategoria atual não estiver nas opções disponíveis, limpe a seleção
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
            // Use fetch via AJAX em vez de dados locais
            this.selectedSubcategory = '';
            this.targetSubcategory = ''; // Limpa a subcategoria alvo ao mudar categoria
            this.fetchSubcategories();
        },

        get placeholderText() {
            if (this.isLoadingSubcategories) {
                return 'Carregando subcategorias...';
            }
            return 'Selecione uma subcategoria';
        }
    };
}
