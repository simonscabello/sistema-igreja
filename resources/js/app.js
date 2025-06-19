import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import 'flowbite';
import './delete-action-alert';
import './theme-toggle';

window.Alpine = Alpine;
Alpine.plugin(mask);
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('subcategoriesContainer');
    const addButton = document.getElementById('addSubcategory');
    if (!container || !addButton) return;
    let subcategoryIndex = container.querySelectorAll('.subcategory-item').length;

    addButton.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'flex items-center space-x-3 subcategory-item';
        newItem.innerHTML = `
            <div class="flex-1">
                <input type="text"
                       name="subcategories[${subcategoryIndex}][name]"
                       placeholder="Nome da subcategoria"
                       class="mt-1 block w-full border-neutral-medium dark:border-gray-600 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-white dark:bg-gray-700 text-neutral-dark dark:text-white">
            </div>
            <div class="flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="hidden" name="subcategories[${subcategoryIndex}][active]" value="0">
                    <input type="checkbox" name="subcategories[${subcategoryIndex}][active]" value="1" class="sr-only peer" checked>
                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 
                                peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full 
                                peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] 
                                after:bg-white after:border-gray-300 after:border after:rounded-full 
                                after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                peer-checked:bg-primary dark:peer-checked:bg-primary">
                    </div>
                    <span class="ms-3 text-sm font-medium text-neutral-dark dark:text-gray-300">Ativo</span>
                </label>
            </div>
            <button type="button" class="remove-subcategory inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Remover
            </button>
        `;
        container.appendChild(newItem);
        subcategoryIndex++;
        const items = container.querySelectorAll('.subcategory-item');
        if (items.length > 1) {
            items[0].querySelector('.remove-subcategory').classList.remove('hidden');
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subcategory')) {
            e.target.closest('.subcategory-item').remove();
            const items = container.querySelectorAll('.subcategory-item');
            if (items.length === 1) {
                items[0].querySelector('.remove-subcategory').classList.add('hidden');
            }
            items.forEach((item, index) => {
                const nameInput = item.querySelector('input[name^="subcategories"][name$="[name]"]');
                const activeHiddenInput = item.querySelector('input[name^="subcategories"][name$="[active]"]');
                const activeCheckbox = item.querySelector('input[type="checkbox"][name^="subcategories"][name$="[active]"]');
                if (nameInput) nameInput.name = `subcategories[${index}][name]`;
                if (activeHiddenInput) activeHiddenInput.name = `subcategories[${index}][active]`;
                if (activeCheckbox) activeCheckbox.name = `subcategories[${index}][active]`;
            });
            subcategoryIndex = items.length;
        }
    });
});
