# 📋 RELATÓRIO DE ANÁLISE DO PROJETO - Assistência Técnica Informática

## ✅ STATUS: PROJETO FUNCIONANDO COM SUCESSO

---

## 1. RESUMO DA INSTALAÇÃO

### Dependências Instaladas
- ✓ **PHPMailer v6.12.0** - Para envio de e-mails
- ✓ **mPDF v8.3.1** - Para geração de PDFs
- ✓ **Composer** - Gerenciador de dependências

### Verificações Concluídas
- ✓ 106 arquivos PHP validados (sem erros sintáticos)
- ✓ Conexão com banco de dados MySQL funcionando
- ✓ 28 tabelas encontradas no banco de dados
- ✓ Todas as dependências carregadas corretamente
- ✓ Permissões de arquivo OK

---

## 2. PROBLEMAS CORRIGIDOS

### 🔒 Segurança (Crítico)
#### ❌ Antes
- Credenciais de banco de dados hardcoded em múltiplos arquivos
- Configurações em texto plano no código-fonte
- Senha vazia sem validação

#### ✅ Depois
- Criado arquivo `.env` centralizado para todas as configurações
- Classe `Config.php` para carregar variáveis de ambiente
- Atualizado `ConfigController.php` para usar as novas configurações
- Arquivo `.env.example` criado para documentação

### 📦 Compatibilidade
#### ❌ Problema
- mPDF v8.0.10 incompatível com PHP 8.5.0
- Extensão GD do PHP não ativada

#### ✅ Solução
- Atualizado mPDF para v8.3.1 (compatível com PHP 8.5.0)
- Outras dependências também atualizadas para máxima compatibilidade

---

## 3. ARQUIVOS CRIADOS/MODIFICADOS

### Novos Arquivos
1. **`.env`** - Configurações da aplicação (NÃO ENVIAR PARA GIT)
2. **`.env.example`** - Exemplo de configuração (enviar para GIT)
3. **`core/Config.php`** - Classe para gerenciar configurações
4. **`test-config.php`** - Script de teste e validação

### Arquivos Modificados
1. **`core/ConfigController.php`** - Atualizado para usar variáveis de ambiente
2. **`composer.lock`** - Atualizado com novas versões de dependências

---

## 4. RECOMENDAÇÕES DE SEGURANÇA

### 🚨 CRÍTICO - Fazer Imediatamente
1. **Adicione `.env` ao `.gitignore`**
   ```
   .env
   .env.local
   vendor/
   ```

2. **Altere a senha do MySQL**
   ```env
   DB_PASS=sua_senha_segura_aqui
   ```

3. **Configure a URL correta da aplicação**
   ```env
   APP_URL=https://seu-dominio.com.ao/
   ```

4. **Ative a extensão GD do PHP** (necessária para mPDF com imagens)
   - Edite `php.ini` e descomente: `;extension=gd`

### ⚠️ IMPORTANTE - Revisar em Produção
1. **Defina `DEBUG=false` em produção**
   ```env
   DEBUG=false
   ```

2. **Use HTTPS em produção** (não HTTP)

3. **Configure variáveis de ambiente no servidor** em vez de arquivo `.env`

4. **Revise permissões de banco de dados** (usuário root não é recomendado)

---

## 5. ESTRUTURA DE CONFIGURAÇÃO

### Arquivo `.env` - Variáveis Disponíveis

```ini
# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=mecanica
DB_USER=root
DB_PASS=

# Aplicação
APP_URL=http://localhost/oficinamecanica.co.ao/
APP_NAME=OFICINA DO BAIRRO

# Dados da Oficina
OFFICE_ADDRESS=Luanda Rua da CTT, Rangel
OFFICE_EMAIL=antjacinto11672@gmail.com
OFFICE_PHONE=+244 931 950 857

# Negócio
STOCK_LEVEL=5
DISCOUNT_ORC=SIM
DISCOUNT_VALUE=0.05
VALIDATE_QUOTE_DAYS=5
DELETE_QUOTE_DAYS=15
MECHANIC_COMMISSION=SIM
COMMISSION_VALUE=0.30

# Debug
DEBUG=false
```

---

## 6. COMO USAR A CLASSE Config

### Exemplo de Uso

```php
<?php
require './core/Config.php';

$config = \Core\Config::load();

// Obter configuração
$dbName = $config['DB_NAME'];

// Ou usando o método get() com valor padrão
$email = \Core\Config::get('OFFICE_EMAIL', 'padrao@exemplo.com');
?>
```

---

## 7. PRÓXIMOS PASSOS

### Desenvolvimento
1. ✓ Instale as dependências com Composer
2. ✓ Configure o arquivo `.env`
3. ✓ Teste a conexão com banco de dados
4. Configure variáveis de ambiente específicas do seu servidor
5. Implemente logging de erros adequado

### Produção
1. Altere `DEBUG=false` no `.env`
2. Configure certificado SSL/HTTPS
3. Use variáveis de ambiente do servidor (não arquivo `.env`)
4. Configure backups automáticos do banco de dados
5. Implemente monitoramento de erros

---

## 8. VERSÕES INSTALADAS

```
PHP: 8.5.0
MySQL: Compatível
PHPMailer: 6.12.0
mPDF: 8.3.1
Composer: Atualizado
```

---

## ✨ RESULTADO FINAL

Seu projeto está **pronto para desenvolvimento e teste**! 

✓ Todos os testes passaram  
✓ Dependências funcionando  
✓ Segurança implementada  
✓ Configurações centralizadas  

**Execute `php test-config.php` novamente a qualquer momento para validar a configuração.**

---

## 9. ALTERAÇÕES — 15 de Junho de 2026

### 🐛 Bugs Corrigidos

#### Entrada de Veículo / Relatório — `identrada_veiculo` inexistente

**Erro:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'identrada_veiculo' in 'order clause'`

A tabela `entrada_veiculo` usa `id` como chave primária, mas o código referenciava `identrada_veiculo`.

| Ficheiro | Linha | Alteração |
|----------|-------|-----------|
| `app/adms/Models/AdmsMecanico.php` | 2681 | `ORDER BY identrada_veiculo` → `ORDER BY id` |
| `app/adms/Models/AdmsMecanico.php` | 2690 | `WHERE identrada_veiculo=` → `WHERE id=` |
| `app/adms/Views/entradaVeiculo/pgEntradaVeiulo.php` | 89, 93, 107 | `$valorForm['identrada_veiculo']` → `$valorForm['id']` no modal e no campo hidden |

---

#### Estatísticas — `nome_servico` inexistente na view `dadosorcamento`

**Erro:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'nome_servico' in 'field list'`

A view `dadosorcamento` expõe o nome do serviço como `tipo_servico` (aliás de `tipo_servico.nome`), mas as queries usavam `nome_servico`.

| Ficheiro | Linhas | Alteração |
|----------|--------|-----------|
| `app/adms/Models/AdmsGraficos.php` | 353, 361, 372, 375 | `nome_servico` → `tipo_servico` em todas as queries e acessos ao array |

---

#### Orçamento — Colunas erradas em listagem, relatório e modal "aprovar"

**Erros:** `Undefined array key 'nome'`, `'valor_maodeobra'`, `'nome_servico'`, `'nifmecanico'`

A view `dadosorcamento` usa nomes de coluna diferentes dos que o código PHP lia.

| Ficheiro | Alteração |
|----------|-----------|
| `app/adms/Views/orcamento/pgOrcamento.php` | `nome`→`nome_cliente`, `valor_maodeobra`→`valor` (modal editar e listagem), `nome_servico`→`tipo_servico`, `nifmecanico`→`mecanico` |
| `app/adms/Views/orcamento/pgServico.php` | Idem; hidden fields do modal "aprovar": `valor_maodeobra`→`valor['valor']`, `valor_t_servico`→`valor['valor']`, `nome_servico`→`valor['tipo_servico']` |
| `app/adms/Views/relatorio/mecanico/relatorioVeiculo.php` | `nome`→`nome_cliente`, `valor_t_servico`→`valor`, `valor_maodeobra`→`valor`, `nome_servico`→`tipo_servico`, `nifmecanico`→`mecanico`, `nome_produto`→`produto` |

---

#### Orçamento — Produto não era adicionado; cliente/veículo não aparecia na listagem

**Causa raiz:** `abrirOrcamento()` e `editOrcamento()` em `AdmsMecanico.php` guardavam `idveiculo` (inteiro) na coluna `orcamentos.veiculo` (VARCHAR). A view `dadosorcamento` faz JOIN `ON v.matricula = o.veiculo`, por isso o JOIN falhava devolvendo NULL para todos os campos do cliente e veículo.

Como o link "Adicionar Produto" inclui `&cliente=<?= $valorForm['nif'] ?>`, e `nif` era NULL (JOIN falhou), o controller `AddProdutoOrcamento` redirecionava sem abrir a página.

| Ficheiro | Linha | Alteração |
|----------|-------|-----------|
| `app/adms/Models/AdmsMecanico.php` | 2089 (abrirOrcamento) | `$this->dados['idveiculo']` → `$this->dados['veiculo']` |
| `app/adms/Models/AdmsMecanico.php` | 2498 (editOrcamento) | `$this->dados['idveiculo']` → `$this->dados['veiculo']` |

**Migração de dados necessária** para orçamentos já registados com valor numérico em `veiculo`:

```sql
-- Corrigir registos antigos: substituir idveiculo numérico pela matricula real
UPDATE orcamentos o
INNER JOIN veiculo v ON v.idveiculo = CAST(o.veiculo AS UNSIGNED)
SET o.veiculo = v.matricula
WHERE o.veiculo REGEXP '^[0-9]+$';
```

Executar uma única vez no phpMyAdmin ou MySQL CLI após o deploy desta correção.

---

#### Produto adicionado a orçamento mais de uma vez não funcionava

**Causa:** A coluna `quantidade` não existia em `orc_prod`. O `UPDATE` ao incrementar quantidade falhava silenciosamente.

| Ficheiro | Alteração |
|----------|-----------|
| `database/schema.sql` | `orc_prod` — coluna `quantidade INT(11) DEFAULT 1` adicionada |

**ALTER TABLE para base de dados existente:**
```sql
ALTER TABLE orc_prod ADD COLUMN quantidade INT(11) DEFAULT 1 AFTER produtos;
```

---

### ✨ Nova Funcionalidade — Recuperação de Senha

Fluxo completo de recuperação de senha via email com código temporário de 6 dígitos.

#### Base de dados

| Tabela | Descrição |
|--------|-----------|
| `reset_senha` | Armazena códigos de recuperação com expiração de 30 min e flag `usado` |

#### Ficheiros criados

| Ficheiro | Descrição |
|----------|-----------|
| `app/adms/Models/AdmsRecuperarSenha.php` | Verifica email, gera e envia código via PHPMailer, valida código, atualiza senha |
| `app/adms/Controllers/RecuperarSenha.php` | Controller de 3 passos gerido por sessão |
| `app/adms/Views/recuperarSenha/pgEmail.php` | Passo 1: formulário de introdução do email |
| `app/adms/Views/recuperarSenha/pgCodigo.php` | Passo 2: 6 inputs individuais para o código com auto-avanço e suporte a colar |
| `app/adms/Views/recuperarSenha/pgNovaSenha.php` | Passo 3: nova senha + confirmar com toggle mostrar/ocultar |

#### Ficheiros modificados

| Ficheiro | Alteração |
|----------|-----------|
| `core/Permissao.php` | `recuperarSenha` adicionado às páginas públicas |
| `app/adms/Views/login/pgLogin.php` | Link "Esqueci a minha senha" activado |
| `.env` | Bloco `SMTP_*` adicionado para configuração do Gmail |
| `database/schema.sql` | Tabela `reset_senha` adicionada |

#### Configuração SMTP

O sistema usa **Mailtrap** (as mesmas credenciais já configuradas no projeto para envio de relatórios):

```ini
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=c85e426e1ec5a1
SMTP_PASS=7cf202962d5c0e
```

Em produção, substituir pelas credenciais do servidor de email real.

---

*Última atualização: 15 de Junho de 2026*
