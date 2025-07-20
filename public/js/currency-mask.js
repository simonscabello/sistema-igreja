// Função para aplicar máscara de moeda brasileira (R$)
function applyCurrencyMask(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    
    if (value === '') {
        input.value = '';
        return;
    }
    
    // Converte para número e divide por 100 para ter centavos
    value = parseInt(value);
    value = value / 100;
    
    // Formata o número para o padrão brasileiro
    const formattedValue = value.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    input.value = formattedValue;
}

// Função para obter o valor numérico do campo formatado
function getNumericValue(formattedValue) {
    if (!formattedValue) return 0;
    
    // Remove pontos e substitui vírgula por ponto
    const numericValue = formattedValue
        .replace(/\./g, '')
        .replace(',', '.');
    
    return parseFloat(numericValue) || 0;
}

// Função para inicializar máscara de moeda em um campo
function initCurrencyMask(selector) {
    const inputs = document.querySelectorAll(selector);
    
    inputs.forEach(input => {
        // Aplica a máscara quando o usuário digita
        input.addEventListener('input', function() {
            applyCurrencyMask(this);
        });
        
        // Aplica a máscara quando o campo recebe foco
        input.addEventListener('focus', function() {
            if (this.value) {
                applyCurrencyMask(this);
            }
        });
        
        // Previne entrada de caracteres não numéricos
        input.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/\d/.test(char)) {
                e.preventDefault();
            }
        });
        
        // Aplica a máscara inicial se já há valor
        if (input.value) {
            applyCurrencyMask(input);
        }
    });
}

// Função para validar se o valor é válido
function validateCurrencyValue(value) {
    if (!value) return false;
    
    const numericValue = getNumericValue(value);
    return numericValue > 0;
}

// Função para obter o valor para envio ao servidor
function getCurrencyValueForSubmission(formattedValue) {
    const numericValue = getNumericValue(formattedValue);
    return numericValue.toFixed(2);
}

// Inicializa a máscara quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initCurrencyMask('input[data-currency-mask="true"]');
}); 