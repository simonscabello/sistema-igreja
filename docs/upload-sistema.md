# Sistema de Upload de Arquivos - Transações Financeiras

## Visão Geral

O sistema de upload de arquivos foi implementado para permitir que usuários anexem comprovantes, notas fiscais e outros documentos às transações financeiras.

## Funcionalidades Implementadas

### 1. Campo de Upload
- **Localização**: Formulários de criação e edição de transações
- **Tipos aceitos**: PDF, JPG, JPEG, PNG
- **Tamanho máximo**: 10MB
- **Opcional**: O campo não é obrigatório

### 2. Validação
- Validação de tipo de arquivo (mimes: pdf,jpg,jpeg,png)
- Validação de tamanho máximo (10MB)
- Mensagens de erro em português
- Validação tanto no frontend quanto no backend

### 3. Armazenamento
- Arquivos são armazenados de forma segura no servidor
- Uso do sistema de arquivos configurado (local, public, S3)
- Organização por coleções ('comprovantes')
- Relacionamento polimórfico com o modelo File

### 4. Visualização
- **Listagem**: Indicador visual (ícone) quando há anexos
- **Detalhes**: Seção dedicada com preview e botões de ação
- **Edição**: Mostra anexo atual e permite substituição

## Componentes Criados

### Input File Component
**Arquivo**: `resources/views/components/input-file.blade.php`

```blade
<x-input-file 
    label="Anexo (Opcional)" 
    name="attachment" 
    accept=".pdf,.jpg,.jpeg,.png"
    helpText="Anexe comprovantes, notas fiscais ou outros documentos relacionados à transação."
/>
```

**Propriedades**:
- `label`: Rótulo do campo
- `name`: Nome do campo
- `accept`: Tipos de arquivo aceitos
- `helpText`: Texto de ajuda personalizado
- `required`: Se o campo é obrigatório
- `multiple`: Se permite múltiplos arquivos

## Estrutura do Banco de Dados

### Tabela `files`
```sql
- id (primary key)
- original_name (nome original do arquivo)
- extension (extensão do arquivo)
- size (tamanho em bytes)
- mime_type (tipo MIME)
- path (caminho no servidor)
- disk (disco de armazenamento)
- url (URL pública)
- created_at, updated_at
```

### Tabela `fileables` (relacionamento polimórfico)
```sql
- id (primary key)
- file_id (foreign key para files)
- fileable_id (ID do modelo relacionado)
- fileable_type (tipo do modelo relacionado)
- collection (nome da coleção)
- created_at, updated_at
```

## Uso no Código

### Modelo FinancialTransaction
```php
use App\Models\Traits\HasFiles;

class FinancialTransaction extends Model
{
    use HasFactory, HasFiles;
    
    // O trait HasFiles adiciona o método files()
    // $transaction->files('comprovantes')->get()
}
```

### Controller
```php
// Upload de arquivo
if ($request->hasFile('attachment')) {
    $this->fileService->uploadFile(
        file: $request->file('attachment'),
        related: $transaction,
        collection: 'comprovantes'
    );
}

// Listar arquivos
$files = $transaction->files('comprovantes')->get();

// Deletar arquivo
$this->fileService->deleteFile($file);
```

### View
```blade
{{-- Verificar se há anexos --}}
@if($transaction->files('comprovantes')->exists())
    <svg class="w-4 h-4 text-blue-500">...</svg>
@endif

{{-- Listar anexos --}}
@foreach($transaction->files('comprovantes')->get() as $attachment)
    <a href="{{ $attachment->url }}" target="_blank">Visualizar</a>
@endforeach
```

## Segurança

### Validação de Arquivos
- Tipos MIME verificados
- Extensões permitidas
- Tamanho máximo configurado
- Sanitização de nomes de arquivo

### Armazenamento Seguro
- Arquivos fora do diretório público
- URLs geradas dinamicamente
- Controle de acesso via middleware
- Limpeza automática ao deletar registros

## Configuração

### Tipos de Arquivo Permitidos
```php
'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240']
```

### Tamanho Máximo
- Configurado em `php.ini` (`upload_max_filesize`, `post_max_size`)
- Validação adicional no Laravel (10MB)

### Disco de Armazenamento
- Configurado em `config/filesystems.php`
- Suporte para local, public, S3

## Testes

### Seeder de Exemplo
```bash
php artisan db:seed --class=FinancialTransactionAttachmentSeeder
```

### Verificação Manual
1. Acesse `/financial-transactions`
2. Crie uma nova transação com anexo
3. Verifique se o ícone aparece na listagem
4. Acesse os detalhes e verifique a seção de anexos
5. Teste o download e visualização

## Próximos Passos

### Melhorias Possíveis
1. **Múltiplos arquivos**: Permitir upload de vários anexos
2. **Preview de imagens**: Mostrar thumbnail de imagens
3. **Drag & Drop**: Interface mais moderna
4. **Compressão**: Reduzir tamanho de imagens automaticamente
5. **OCR**: Extrair texto de PDFs para busca
6. **Versionamento**: Manter histórico de anexos

### Integração com Outros Módulos
- Membros (documentos pessoais)
- Campanhas (materiais promocionais)
- Departamentos (documentos organizacionais)
- Músicas (partituras, áudios)

## Troubleshooting

### Problemas Comuns

1. **Arquivo não aparece**
   - Verificar permissões do diretório storage
   - Executar `php artisan storage:link`

2. **Erro de validação**
   - Verificar configuração do PHP (upload_max_filesize)
   - Verificar tipos MIME permitidos

3. **Arquivo não deleta**
   - Verificar permissões de escrita
   - Verificar se o arquivo existe no disco

### Logs
```bash
tail -f storage/logs/laravel.log
```

## Conclusão

O sistema de upload foi implementado seguindo as melhores práticas de segurança e UX, proporcionando uma experiência consistente com o resto da aplicação. A arquitetura permite fácil extensão para outros módulos do sistema. 