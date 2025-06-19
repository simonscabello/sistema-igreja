# 📊 API de Relatórios Financeiros Mensais

## Endpoint
```
GET /relatorios/financeiro/mensal
```

## Parâmetros de Query
- `month` (opcional): Mês (1-12). Padrão: mês atual
- `year` (opcional): Ano (YYYY). Padrão: ano atual

## Exemplos de Uso

### Relatório do mês atual
```
GET /relatorios/financeiro/mensal
```

### Relatório de janeiro de 2024
```
GET /relatorios/financeiro/mensal?month=1&year=2024
```

### Relatório de dezembro de 2023
```
GET /relatorios/financeiro/mensal?month=12&year=2023
```

## Estrutura da Resposta

```json
{
  "entradas": {
    "Receitas": {
      "total_categoria": 2680.00,
      "subcategorias": {
        "Dízimos": 1800.00,
        "Ofertas": 560.00,
        "Campanha Reforma": 320.00
      }
    },
    "Ação Social": {
      "total_categoria": 300.00,
      "subcategorias": {
        "Doações Externas": 300.00
      }
    }
  },
  "saidas": {
    "Administração Patrimonial": {
      "total_categoria": 780.16,
      "subcategorias": {
        "Energia": 344.35,
        "Água e Esgoto": 89.22,
        "Internet/Telefone": 99.69
      }
    },
    "Ação Social": {
      "total_categoria": 405.51,
      "subcategorias": {
        "Fraldas Geriátricas": 251.60
      }
    }
  },
  "total_entradas": 2980.00,
  "total_saidas": 1185.67,
  "saldo_mensal": 1794.33,
  "periodo": {
    "mes": 1,
    "ano": 2024,
    "mes_nome": "janeiro"
  }
}
```

## Campos da Resposta

- **entradas**: Transações do tipo "entrada" agrupadas por categoria e subcategoria
- **saidas**: Transações do tipo "saida" agrupadas por categoria e subcategoria
- **total_entradas**: Soma total de todas as entradas do período
- **total_saidas**: Soma total de todas as saídas do período
- **saldo_mensal**: Balancete (entradas - saídas)
- **periodo**: Informações sobre o período do relatório

## Características

- ✅ Agrupamento por categoria e subcategoria
- ✅ Totais consolidados
- ✅ Balancete automático
- ✅ Filtro por mês e ano
- ✅ Resposta em JSON
- ✅ Preparado para futuras exportações (PDF/Excel) 