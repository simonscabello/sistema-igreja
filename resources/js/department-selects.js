// Gerenciamento dos campos de seleção de departamentos
// Mantém uma cópia das opções originais para restaurar sempre que necessário
document.addEventListener('DOMContentLoaded', function() {
    const responsibleSelect = document.getElementById('responsible_members');
    const membersSelect = document.getElementById('members');
    
    if (!responsibleSelect || !membersSelect) return;
    
    let responsibleChoices, membersChoices;

    // Salva as opções originais de membros
    const originalMemberOptions = Array.from(membersSelect.querySelectorAll('option')).map(option => ({
        value: option.value,
        label: option.textContent,
    }));
    
    // Inicializar Choices.js para responsáveis
    responsibleChoices = new Choices(responsibleSelect, {
        removeItemButton: true,
        searchEnabled: true,
        searchPlaceholderValue: 'Buscar líderes...',
        noResultsText: 'Nenhum líder encontrado',
        itemSelectText: '',
        classNames: { containerOuter: 'choices' }
    });
    
    // Função para (re)criar o campo de membros
    function createMembersChoices(selectedValues = []) {
        if (membersChoices) {
            membersChoices.destroy();
        }
        // Limpa o select original
        membersSelect.innerHTML = '';
        // Adiciona as opções válidas
        const selectedResponsibles = responsibleChoices.getValue().map(item => item.value);
        const availableMemberOptions = originalMemberOptions.filter(option =>
            !selectedResponsibles.includes(option.value)
        );
        availableMemberOptions.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option.value;
            opt.textContent = option.label;
            if (selectedValues.includes(option.value)) {
                opt.selected = true;
            }
            membersSelect.appendChild(opt);
        });
        // Recria o Choices
        membersChoices = new Choices(membersSelect, {
            removeItemButton: true,
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar membros...',
            noResultsText: 'Nenhum membro encontrado',
            itemSelectText: '',
            classNames: { containerOuter: 'choices' }
        });
    }

    // Sempre que mudar líderes, recria o campo de membros
    function updateMembersOptions() {
        // Recupera os membros atualmente selecionados (antes de atualizar as opções)
        const currentSelectedMembers = membersChoices ? membersChoices.getValue().map(item => item.value) : [];
        createMembersChoices(currentSelectedMembers);
    }

    // Inicializa o campo de membros na primeira vez
    createMembersChoices();

    // Event listener para mudanças no campo de responsáveis
    responsibleChoices.passedElement.element.addEventListener('change', updateMembersOptions);
    responsibleChoices.passedElement.element.addEventListener('removeItem', updateMembersOptions);
}); 