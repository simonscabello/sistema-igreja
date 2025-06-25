// Gerenciamento dos campos de seleção de departamentos na edição
document.addEventListener('DOMContentLoaded', function() {
    const responsibleSelect = document.getElementById('responsible_members');
    const membersSelect = document.getElementById('members');
    
    if (!responsibleSelect || !membersSelect) return;
    
    let responsibleChoices, membersChoices;

    // Captura os valores já selecionados na edição
    const getSelectedValues = (select) => {
        return Array.from(select.querySelectorAll('option:checked')).map(option => option.value);
    };

    // Salva as opções originais
    const originalOptions = Array.from(membersSelect.querySelectorAll('option')).map(option => ({
        value: option.value,
        label: option.textContent,
    }));
    
    // Função para (re)criar o campo de líderes
    function createResponsibleChoices(selectedValues = []) {
        if (responsibleChoices) {
            responsibleChoices.destroy();
        }
        responsibleSelect.innerHTML = '';
        
        // Remove da lista de líderes os membros já selecionados
        const selectedMembers = membersChoices ? membersChoices.getValue().map(item => item.value) : [];
        const availableLeaderOptions = originalOptions.filter(option =>
            !selectedMembers.includes(option.value)
        );
        
        availableLeaderOptions.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option.value;
            opt.textContent = option.label;
            if (selectedValues.includes(option.value)) {
                opt.selected = true;
            }
            responsibleSelect.appendChild(opt);
        });
        
        responsibleChoices = new Choices(responsibleSelect, {
            removeItemButton: true,
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar líderes...',
            noResultsText: 'Nenhum líder encontrado',
            itemSelectText: '',
            classNames: { containerOuter: 'choices' }
        });
    }

    // Função para (re)criar o campo de membros
    function createMembersChoices(selectedValues = []) {
        if (membersChoices) {
            membersChoices.destroy();
        }
        membersSelect.innerHTML = '';
        
        // Remove da lista de membros os líderes já selecionados
        const selectedResponsibles = responsibleChoices ? responsibleChoices.getValue().map(item => item.value) : [];
        const availableMemberOptions = originalOptions.filter(option =>
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
        
        membersChoices = new Choices(membersSelect, {
            removeItemButton: true,
            searchEnabled: true,
            searchPlaceholderValue: 'Buscar membros...',
            noResultsText: 'Nenhum membro encontrado',
            itemSelectText: '',
            classNames: { containerOuter: 'choices' }
        });
    }

    // Atualiza ambos os campos de forma bidirecional
    function updateBothFields() {
        // Salva seleções atuais
        const currentSelectedLeaders = responsibleChoices ? responsibleChoices.getValue().map(item => item.value) : [];
        const currentSelectedMembers = membersChoices ? membersChoices.getValue().map(item => item.value) : [];
        
        // Atualiza líderes (removendo membros selecionados)
        createResponsibleChoices(currentSelectedLeaders);
        // Atualiza membros (removendo líderes selecionados)
        createMembersChoices(currentSelectedMembers);
    }

    // Captura os valores iniciais já selecionados
    const initialResponsibleValues = getSelectedValues(responsibleSelect);
    const initialMembersValues = getSelectedValues(membersSelect);

    // Inicializa ambos os campos com os valores já selecionados
    createResponsibleChoices(initialResponsibleValues);
    createMembersChoices(initialMembersValues);

    // Eventos para atualizar sempre que houver mudança em qualquer campo
    responsibleSelect.addEventListener('change', updateBothFields);
    membersSelect.addEventListener('change', updateBothFields);
}); 