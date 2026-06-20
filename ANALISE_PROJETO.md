# Relatório de Análise do Projecto — Assistência Técnica Informática

## Status: Pronto para Uso — v3.0.1

---

## 1. Resumo da Instalação

### Dependências Instaladas
- **PHPMailer v6.12.0** — Envio de e-mails
- **mPDF v8.3.1** — Geração de PDFs
- **Composer** — Gestão de dependências

### Verificações Concluídas
- PHP 8.2 compatível
- Conexão com BD `manutencao` activa
- 27 tabelas e 6 views confirmadas
- Todas as dependências carregadas
- Permissões de ficheiro OK

---

## 2. Configuração de Ambiente

### Ficheiro `.env` — Variáveis Disponíveis

```ini
# Base de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=manutencao
DB_USER=root
DB_PASS=

# Aplicação
APP_URL=http://localhost/oficina-de-informatica/
APP_NAME=ASSISTÊNCIA TÉCNICA INFORMÁTICA

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
TECHNICIAN_COMMISSION=SIM
COMMISSION_VALUE=0.30

# Debug
DEBUG=false

# SMTP
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=c85e426e1ec5a1
SMTP_PASS=7cf202962d5c0e
```

---

## 3. Como Usar a Classe Config

```php
<?php
require './core/Config.php';

$config = \Core\Config::load();

// Aceder a uma variável
$dbName = $config['DB_NAME']; // 'manutencao'

// Com valor padrão
$email = \Core\Config::get('OFFICE_EMAIL', 'padrao@exemplo.com');
```

---

## 4. Recomendações de Segurança

### Crítico — Produção
1. Altere a senha do MySQL no `.env`: `DB_PASS=senha_segura`
2. Configure a URL real: `APP_URL=https://seu-dominio.ao/`
3. Defina `DEBUG=false`
4. Use HTTPS
5. Não use o utilizador `root` do MySQL em produção

### Importante
- O ficheiro `.env` está protegido no `.gitignore`
- Credenciais SMTP do Mailtrap são apenas para desenvolvimento — configure servidor SMTP real em produção

---

## 5. Estrutura de Acesso por Nível

| Nível (`usuario.nivel`) | Descrição | Módulos |
|-------------------------|-----------|---------|
| `adimin` | Administrador | Acesso total |
| `tecnico` | Técnico | Orçamentos, Serviços, Comissões |
| `recep` | Recepcionista | Clientes, Equipamentos, Contas, Orçamentos |

---

## 6. Base de Dados — Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `usuario` | Utilizadores do sistema (adimin, tecnico, recep) |
| `tecnicos` | Técnicos de informática |
| `recepcionista` | Recepcionistas |
| `clientes` | Clientes |
| `equipamento` | Equipamentos informáticos |
| `orcamentos` | Orçamentos e ordens de serviço |
| `orc_prod` | Produtos associados a orçamentos |
| `produto` | Produtos / peças |
| `categoria` | Categorias de produtos |
| `fornecedor` | Fornecedores |
| `tipo_servico` | Tipos de serviço técnico |
| `contas_apagar` | Contas a pagar |
| `conntas_areceber` | Contas a receber |
| `movimentacao` | Fluxo de caixa |
| `compras` | Compras a fornecedores |
| `vendas` | Vendas |
| `comissao` | Comissões dos técnicos |
| `entrada_equipamento` | Registo de entrada de equipamentos |
| `reset_senha` | Tokens de recuperação de senha |

### Views
| View | Descrição |
|------|-----------|
| `dadosorcamento` | Orçamentos com cliente, equipamento e serviço |
| `dadosOrcamentosCompletoComProdutos` | Orçamentos com peças associadas |
| `dadosClienteEquipamento` | Clientes com equipamentos |
| `dadosClienteVeiculo` | Alias de compatibilidade |
| `dadosProduto` | Produtos com fornecedor e categoria |
| `compras_contaspagar_dadosproduto` | Compras associadas a contas a pagar |

---

## 7. Próximos Passos

### Desenvolvimento
1. Configure `.env` com as suas credenciais
2. Execute `php test-config.php` para validar
3. Aceda a `http://localhost/oficina-de-informatica/`

### Produção
1. `DEBUG=false`
2. HTTPS obrigatório
3. Servidor SMTP real (não Mailtrap)
4. Backups automáticos da BD
5. Utilizador MySQL sem privilégios root

---

## 8. Versões

| Componente | Versão |
|-----------|--------|
| PHP | 8.2 |
| MySQL | 8.0+ |
| PHPMailer | 6.12.0 |
| mPDF | 8.3.1 |
| Sistema | 3.0.1 |

---

*Última atualização: 19 de Junho de 2026*
