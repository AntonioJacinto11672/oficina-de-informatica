# Relatório de Análise do Projeto — Sistema de Gestão de Manutenção de Equipamentos Informáticos (ULA)

## Status: Adaptado ao contexto institucional — v5.0.0

---

## 1. Resumo da Adaptação

O sistema deixou de ser uma oficina comercial (mecânica → assistência técnica informática) e passou a ser uma solução **institucional interna** da Universidade Lusíada de Angola: sem clientes externos, vendas, orçamentos comerciais, faturas ou comissões. A arquitetura Cliente-Servidor + MVC foi mantida integralmente.

Ver `RELATORIO_TFC_ADAPTACAO.md` para o detalhe completo da auditoria, correções e justificações; ver `CHANGELOG.md` para o histórico de versões.

### Verificações Concluídas
- PHP 8.2 compatível, `php -l` sem erros em todos os ficheiros
- Base de dados `manutencao` reconstruída de raiz (schema institucional único)
- Fluxo Ocorrência → Diagnóstico → Execução → Conclusão → Histórico testado ponta-a-ponta
- Controlo de acesso por papel (Gerente/Técnico) testado e confirmado
- Zero avisos/erros PHP nas rotas testadas (ver relatório final)

---

## 2. Ficheiro `.env` — Variáveis Disponíveis

```ini
# Base de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=manutencao
DB_USER=root
DB_PASS=

# Aplicação
APP_URL=http://localhost/oficina-de-informatica/
APP_NAME=Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos da Universidade Lusíada de Angola

# Dados Institucionais (Departamento de TI)
UNIVERSITY_ADDRESS=Luanda, Mutamba Largo do Lumeji, nº 11/12
IT_DEPT_EMAIL=geral@ula.co.ao
IT_DEPT_PHONE=+244 930 038 044

# Stock
STOCK_LEVEL=5

# Debug
DEBUG=false

# SMTP
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=
SMTP_PASS=
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
$email = \Core\Config::get('IT_DEPT_EMAIL', 'geral@ula.co.ao');
```

---

## 4. Recomendações de Segurança

### Crítico — Produção
1. Altere a senha do MySQL no `.env`: `DB_PASS=senha_segura`
2. Configure a URL real: `APP_URL=https://intranet.ula.co.ao/manutencao/`
3. Defina `DEBUG=false`
4. Use HTTPS
5. Não use o utilizador `root` do MySQL em produção
6. Altere a senha do Gerente (`gerente.ti@ula.co.ao`) imediatamente após o primeiro acesso

### Já corrigido nesta versão
- Palavras-passe em `password_hash()`/`password_verify()` (antes: MD5)
- Auto-seed do utilizador admin removido de cada pedido HTTP (corria em todas as páginas)
- Fuga de dados por `var_dump()` no login removida
- `session_destroy()` completo no logout (antes: `unset()` seletivo)
- Controlo de acesso por papel em `core/Permissao.php` (antes: só validava sessão iniciada, qualquer papel acedia a qualquer rota por URL direta)
- SQL com `bindParam`/prepared statements consistentes (antes: várias queries com concatenação direta de variáveis)

---

## 5. Estrutura de Acesso por Papel

| Papel (`usuario.nivel`) | Descrição | Módulos |
|--------------------------|-----------|---------|
| `gerente` | Gerente de TI | Acesso total: cadastros, manutenção, stock, relatórios, configurações |
| `tecnico` | Técnico de Informática | Ocorrências, Diagnósticos, Execuções, Planeamento, Equipamentos, Histórico, Perfil |

---

## 6. Base de Dados — Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `usuario` | Contas de acesso (gerente, tecnico) |
| `tecnicos` | Ficha do técnico |
| `departamentos` | Departamentos da Universidade |
| `equipamento` | Equipamentos informáticos |
| `categoria_equipamento` | Tipos de equipamento |
| `equipamentos_abatidos` | Workflow de abatimento |
| `fornecedor` | Fornecedores de equipamentos/peças/consumíveis |
| `categoria` / `produto` | Categorias e Peças/Consumíveis (stock interno) |
| `tipo_manutencao` | Catálogo de tipos de manutenção |
| `ocorrencias` / `ocorrencia_equipamento` / `ocorrencia_historico` | Ocorrências e auditoria de estado |
| `diagnostico` | Diagnósticos técnicos |
| `execucao_manutencao` / `execucao_peca` | Execuções e peças consumidas |
| `plano_manutencao_preventiva` / `plano_manutencao_lembrete` | Planeamento preventivo |
| `movimento_estoque` | Ledger de entradas/saídas de stock |
| `compras` | Compras a fornecedores |
| `reset_senha` | Tokens de recuperação de senha |
| `control_usuario` | Log de acessos |

### Views
| View | Descrição |
|------|-----------|
| `dadosProduto` | Peças com categoria e fornecedor |
| `dadosEquipamento` | Equipamentos com departamento, categoria, responsável e fornecedor |
| `dadosCompras` | Compras com peça e fornecedor |

---

## 7. Próximos Passos

### Desenvolvimento
1. Configure `.env` com as suas credenciais
2. Importe `database/schema.sql`
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
| MySQL/MariaDB | 8.0+ / 10.4+ |
| PHPMailer | 6.x |
| Sistema | 5.0.0 |

---

*Última atualização: 04 de Agosto de 2026*
