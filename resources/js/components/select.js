// Componente de Select Avançado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos os selects avançados
    initializeAdvancedSelects();
});

function initializeAdvancedSelects() {
    const selects = document.querySelectorAll('[data-advanced-select]');
    
    selects.forEach(select => {
        new AdvancedSelect(select);
    });
}

class AdvancedSelect {
    constructor(element) {
        this.element = element;
        this.options = JSON.parse(element.dataset.options || '[]');
        this.selected = JSON.parse(element.dataset.selected || '[]');
        this.multiple = element.dataset.multiple === 'true';
        this.imageField = element.dataset.imageField || null;
        this.maxVisible = parseInt(element.dataset.maxVisible) || 5;
        this.placeholder = element.dataset.placeholder || 'Selecione...';
        this.name = element.dataset.name || '';
        
        this.isOpen = false;
        this.searchTerm = '';
        this.filteredOptions = [...this.options];
        
        this.init();
    }
    
    init() {
        this.createSelectStructure();
        this.bindEvents();
        this.updateDisplay();
        this.updateOptionsList();
    }
    
    createSelectStructure() {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative';
        
        // Input principal
        const input = document.createElement('div');
        input.className = 'flex items-center justify-between w-full px-3 py-2 text-sm border border-neutral-medium dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-neutral-dark dark:text-white cursor-pointer hover:border-primary focus:border-primary focus:ring-1 focus:ring-primary';
        input.innerHTML = `
            <div class="flex-1 min-w-0">
                <div class="selected-items"></div>
                <input type="text" class="search-input w-full px-3 py-2 text-sm border border-neutral-medium dark:border-gray-600 focus:border-primary focus:ring-primary rounded-md shadow-sm bg-white dark:bg-gray-700 text-neutral-dark dark:text-white placeholder-gray-500" placeholder="${this.placeholder}">
            </div>
            <svg class="w-4 h-4 text-neutral-medium dark:text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        `;
        
        // Dropdown
        const dropdown = document.createElement('div');
        dropdown.className = 'absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-neutral-medium dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto hidden';
        dropdown.innerHTML = '<div class="options-list"></div>';
        
        // Input hidden para formulário
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = this.name;
        hiddenInput.value = this.multiple ? JSON.stringify(this.selected) : (this.selected[0] || '');
        
        wrapper.appendChild(input);
        wrapper.appendChild(dropdown);
        wrapper.appendChild(hiddenInput);
        
        // Substituir o elemento original
        this.element.parentNode.insertBefore(wrapper, this.element);
        this.element.style.display = 'none';
        
        this.wrapper = wrapper;
        this.input = input;
        this.dropdown = dropdown;
        this.searchInput = input.querySelector('.search-input');
        this.selectedItemsContainer = input.querySelector('.selected-items');
        this.optionsList = dropdown.querySelector('.options-list');
        this.hiddenInput = hiddenInput;
        this.arrow = input.querySelector('svg');
    }
    
    bindEvents() {
        // Toggle dropdown
        this.input.addEventListener('click', (e) => {
            if (e.target !== this.searchInput) {
                this.toggleDropdown();
            }
        });
        
        // Busca
        this.searchInput.addEventListener('input', (e) => {
            this.searchTerm = e.target.value.toLowerCase();
            this.filterOptions();
            this.updateOptionsList();
        });
        
        // Foco no input de busca
        this.input.addEventListener('click', () => {
            this.searchInput.focus();
        });
        
        // Fechar dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.closeDropdown();
            }
        });
        
        // Navegação com teclado
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const firstOption = this.optionsList.querySelector('.option-item');
                if (firstOption) {
                    this.toggleOption(firstOption.dataset.value);
                }
            }
        });
    }
    
    toggleDropdown() {
        this.isOpen = !this.isOpen;
        
        if (this.isOpen) {
            this.dropdown.classList.remove('hidden');
            this.arrow.style.transform = 'rotate(180deg)';
            this.searchInput.focus();
        } else {
            this.closeDropdown();
        }
    }
    
    closeDropdown() {
        this.isOpen = false;
        this.dropdown.classList.add('hidden');
        this.arrow.style.transform = 'rotate(0deg)';
        this.searchInput.value = '';
        this.searchTerm = '';
        this.filterOptions();
        this.updateOptionsList();
    }
    
    filterOptions() {
        this.filteredOptions = this.options.filter(option => 
            option.name.toLowerCase().includes(this.searchTerm)
        );
    }
    
    updateOptionsList() {
        this.optionsList.innerHTML = '';
        
        if (this.filteredOptions.length === 0) {
            this.optionsList.innerHTML = '<div class="px-3 py-2 text-sm text-neutral-medium dark:text-gray-400">Nenhum resultado encontrado</div>';
            return;
        }
        
        this.filteredOptions.slice(0, this.maxVisible).forEach(option => {
            const optionElement = document.createElement('div');
            optionElement.className = 'option-item flex items-center px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600';
            optionElement.dataset.value = option.id;
            
            const isSelected = this.selected.includes(option.id);
            if (isSelected) {
                optionElement.classList.add('bg-primary/10', 'dark:bg-primary/20');
            }
            
            let optionContent = '';
            
            // Imagem (se configurada)
            if (this.imageField && option[this.imageField]) {
                optionContent += `
                    <img src="${option[this.imageField]}" alt="${option.name}" class="w-6 h-6 rounded-full mr-2 object-cover">
                `;
            }
            
            // Nome (em negrito se selecionado)
            const nameClass = isSelected ? 'font-bold' : '';
            optionContent += `<span class="${nameClass} flex-1">${option.name}</span>`;
            
            // Ícone de check (se selecionado)
            if (isSelected) {
                optionContent += `
                    <svg class="w-4 h-4 text-primary ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            }
            
            optionElement.innerHTML = optionContent;
            
            optionElement.addEventListener('click', () => {
                this.toggleOption(option.id);
            });
            
            this.optionsList.appendChild(optionElement);
        });
        
        // Mostrar indicador de mais itens se necessário
        if (this.filteredOptions.length > this.maxVisible) {
            const moreIndicator = document.createElement('div');
            moreIndicator.className = 'px-3 py-2 text-xs text-neutral-medium dark:text-gray-400 text-center border-t border-gray-200 dark:border-gray-600';
            moreIndicator.textContent = `+${this.filteredOptions.length - this.maxVisible} mais...`;
            this.optionsList.appendChild(moreIndicator);
        }
    }
    
    toggleOption(value) {
        if (this.multiple) {
            const index = this.selected.indexOf(value);
            if (index > -1) {
                this.selected.splice(index, 1);
            } else {
                this.selected.push(value);
            }
        } else {
            this.selected = [value];
            this.closeDropdown();
        }
        
        this.updateDisplay();
        this.updateOptionsList();
        this.updateHiddenInput();
    }
    
    updateDisplay() {
        if (this.selected.length === 0) {
            this.selectedItemsContainer.innerHTML = '';
            return;
        }
        
        if (this.multiple) {
            const selectedOptions = this.options.filter(option => this.selected.includes(option.id));
            const displayText = selectedOptions.map(option => option.name).join(', ');
            
            if (displayText.length > 50) {
                this.selectedItemsContainer.innerHTML = `<span class="text-sm">${selectedOptions.length} item(s) selecionado(s)</span>`;
            } else {
                this.selectedItemsContainer.innerHTML = `<span class="text-sm">${displayText}</span>`;
            }
        } else {
            const selectedOption = this.options.find(option => option.id == this.selected[0]);
            if (selectedOption) {
                this.selectedItemsContainer.innerHTML = `<span class="text-sm">${selectedOption.name}</span>`;
            }
        }
    }
    
    updateHiddenInput() {
        if (this.multiple) {
            this.hiddenInput.value = JSON.stringify(this.selected);
        } else {
            this.hiddenInput.value = this.selected[0] || '';
        }
        
        // Disparar evento para notificar mudanças
        this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

// Função global para criar selects avançados
window.createAdvancedSelect = function(element, options) {
    return new AdvancedSelect(element);
}; 