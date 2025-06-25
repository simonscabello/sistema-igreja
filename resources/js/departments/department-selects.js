// Gerenciamento dos campos de seleção de departamentos (apenas para criação)
document.addEventListener('DOMContentLoaded', function() {
    const responsibleSelect = document.getElementById('responsible_members');
    const membersSelect = document.getElementById('members');
    
    if (!responsibleSelect || !membersSelect) return;
    
    // Verifica se está na página de criação (não tem valores pré-selecionados)
    const hasPreSelectedValues = responsibleSelect.querySelectorAll('option:checked').length > 0 || 
                                membersSelect.querySelectorAll('option:checked').length > 0;
    
    // Se tem valores pré-selecionados, não executa este script (deixa para o department-edit.js)
    if (hasPreSelectedValues) return;
    
    let responsibleChoices, membersChoices;

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

    // Inicializa ambos os campos
    createResponsibleChoices();
    createMembersChoices();

    // Eventos para atualizar sempre que houver mudança em qualquer campo
    document.getElementById('responsible_members').addEventListener('change', updateBothFields);
    document.getElementById('members').addEventListener('change', updateBothFields);
}); 