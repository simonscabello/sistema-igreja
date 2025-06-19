# 🌱 Seeder de Transações Financeiras - Julho/2024

## 📋 Descrição
Seeder criado para popular a tabela de transações financeiras com dados baseados no relatório de Julho/2024.

## 🚀 Como Executar

### Opção 1: Comando Específico
```bash
php artisan seed:transaction-report
```

### Opção 2: Via DatabaseSeeder
```bash
php artisan db:seed
```

## 📊 Dados Inseridos

### Categorias e Subcategorias Criadas:
- **Administração Patrimonial**
  - Energia
  - Internet/Telefone
  - Cópias e Impressões

- **Ação Social**
  - Fraldas Geriátricas
  - Ajuda Cristolândia

- **Educação Cristã**
  - Revista Lições Bíblicas Adolescentes
  - Revista Lições Bíblicas Adultos

- **Evangelismo e Missões**
  - Viagem Missionária

- **Administração Geral**
  - Ajuda de Custo Membros
  - Limpeza
  - Água e Esgoto

### Transações Criadas:
| Subcategoria | Valor | Data |
|--------------|-------|------|
| Energia | R$ 344,35 | 2024-07-05 |
| Internet/Telefone | R$ 99,69 | 2024-07-10 |
| Cópias e Impressões | R$ 3,00 | 2024-07-15 |
| Fraldas Geriátricas | R$ 251,60 | 2024-07-08 |
| Ajuda Cristolândia | R$ 53,91 | 2024-07-12 |
| Revista Lições Bíblicas Adolescentes | R$ 47,95 | 2024-07-03 |
| Revista Lições Bíblicas Adultos | R$ 71,91 | 2024-07-03 |
| Viagem Missionária | R$ 135,00 | 2024-07-20 |
| Ajuda de Custo Membros | R$ 180,00 | 2024-07-25 |
| Limpeza | R$ 70,00 | 2024-07-18 |
| Água e Esgoto | R$ 89,22 | 2024-07-22 |

## 🔧 Características Técnicas

- **Tipo**: Todas as transações são do tipo `saida`
- **Período**: Julho/2024 (datas distribuídas ao longo do mês)
- **Relacionamentos**: Corretamente vinculadas às subcategorias e categorias
- **Descrições**: Incluem referência à subcategoria e período

## 📁 Arquivos Criados/Modificados

- `database/seeders/TransactionReportSeeder.php` - Seeder principal
- `app/Console/Commands/SeedTransactionReport.php` - Comando artisan
- `database/seeders/DatabaseSeeder.php` - Atualizado para incluir o seeder

## ✅ Verificação

Para verificar se os dados foram inseridos corretamente:

```bash
php artisan tinker --execute="echo 'Transações: ' . App\Models\FinancialTransaction::count() . PHP_EOL;"
```

Total esperado: **11 transações** 