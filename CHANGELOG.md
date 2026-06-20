# CHANGELOG — Sistema de Gestão de Assistência Técnica Informática

---

## Versão 3.0.1 — Correcções Pós-Migração

**Data:** 2026-06-19
**Autor:** António Jacinto

### Bugs Corrigidos

#### Base de Dados — Nome da BD alterado para `manutencao`

O nome da base de dados foi alterado de `mecanica` para `manutencao`. Todos os fallbacks hardcoded foram atualizados.

| Ficheiro | Alteração |
|----------|-----------|
| `.env` | `DB_NAME=manutencao` |
| `health.php` | fallback `'mecanica'` → `'manutencao'` |
| `migrar.php` | DSN `dbname=mecanica` → `dbname=manutencao` |
| `core/ConfigController.php` | fallback `'mecanica'` → `'manutencao'` |
| `validate-project.php` | fallback `'mecanica'` → `'manutencao'` |
| `test-config.php` | fallback `"mecanica"` → `"manutencao"` |
| `app/adms/Models/Conn.php` | fallback `'mecanica'` → `'manutencao'` |
| `database/migrate_to_informatica.sql` | `USE mecanica` → `USE manutencao`; todas as `table_schema='mecanica'` → `'manutencao'` |
| `database/schema.sql` | coluna `modified` adicionada a `contas_apagar`; view `compras_contaspagar_dadosproduto` adicionada |

**Migração executada:** `database/schema.sql` aplicado na BD `manutencao` — 27 tabelas e 6 views criadas com sucesso.

---

#### Contas a Pagar — Página não carregava (crash PHP 8.2)

**Sintoma:** Ao entrar em Contas à Pagar, a página crashava a meio da renderização. Os botões de deletar e aprovar não abriam modal (URL mostrava `#`).

**Causa raiz:** A view `compras_contaspagar_dadosproduto` não existia na BD. `mysqli_query()` retornava `false` e em PHP 8.2 `mysqli_fetch_assoc(false)` lança `TypeError`, interrompendo a renderização antes do footer com jQuery/Bootstrap.

**Correcções:**

| Ficheiro | Alteração |
|----------|-----------|
| `app/adms/Views/contas/pgPagar.php` | Três chamadas `mysqli_fetch_assoc()` protegidas com `($result !== false) ? ... : null` |
| BD `manutencao` | View `compras_contaspagar_dadosproduto` criada (une `compras`, `contas_apagar`, `produto`, `categoria`, `fornecedor`) |
| BD `manutencao` | Coluna `modified` adicionada à tabela `contas_apagar` |
| `database/schema.sql` | View e coluna `modified` incluídas no schema |

---

#### Orçamentos — `HY093: Invalid parameter number` ao abrir orçamento

**Causa:** `INSERT INTO orcamentos` usava coluna `mecanico` e placeholder `:mecanico`, mas `bindParam` já usava `:tecnico`. Mismatch PDO.

| Ficheiro | Linha | Alteração |
|----------|-------|-----------|
| `app/adms/Models/AdmsTecnico.php` | 2086 | `mecanico` / `:mecanico` → `tecnico` / `:tecnico` no INSERT |

---

#### Produtos em Orçamento — `HY093` ao adicionar produto

**Causa:** Dois `INSERT INTO conntas_areceber` usavam coluna `mecanico` / `:mecanico` mas `bindParam` usava `:tecnico`.

| Ficheiro | Linhas | Alteração |
|----------|--------|-----------|
| `app/adms/Models/AdmsTecnico.php` | 2317 | `mecanico` / `:mecanico` → `tecnico` / `:tecnico` |
| `app/adms/Models/AdmsTecnico.php` | 2602 | `mecanico` / `:mecanico` → `tecnico` / `:tecnico` |

---

#### Aprovar Orçamento na Recepção — `bindParam(): cannot be passed by reference`

**Causa:** PHP 8.2 não aceita expressões temporárias (`$this->dados['marca'] ?? ''`) como argumento por referência em `bindParam()`.

| Ficheiro | Linha | Alteração |
|----------|-------|-----------|
| `app/adms/Models/AdmsTecnico.php` | 2680 | `$this->dados['marca'] ?? ''`, `$this->dados['modelo'] ?? ''` e `$_SESSION['nif']` extraídos para variáveis locais antes de `bindParam` |

---

## Versão 3.0.0 — Sistema de Gestão de Assistência Técnica Informática

**Data:** 2026-06-17
**Autor:** António Jacinto
**Descrição:** Transformação completa do Sistema de gestão de Assistência técnica para sistema de gestão de assistência técnica informática. Toda a nomenclatura do projeto — classes, ficheiros, métodos, colunas de base de dados e rotas — foi atualizada para refletir o novo domínio.

---

### 1. Ficheiros PHP Renomeados

#### Controllers (`app/adms/Controllers/`)
| Ficheiro Antigo | Ficheiro Novo | Classe Antiga | Classe Nova |
|---|---|---|---|
| `Mecanico.php` | `Tecnico.php` | `Mecanico` | `Tecnico` |
| `Veiculo.php` | `Equipamento.php` | `Veiculo` | `Equipamento` |
| `EntradaVeiculo.php` | `EntradaEquipamento.php` | `entradaVeiculo` | `EntradaEquipamento` |
| `RelatorioMecanica.php` | `RelatorioTecnico.php` | `RelatorioMecanica` | `RelatorioTecnico` |

#### Models (`app/adms/Models/`)
| Ficheiro Antigo | Ficheiro Novo | Classe Antiga | Classe Nova |
|---|---|---|---|
| `AdmsMecanico.php` | `AdmsTecnico.php` | `AdmsMecanico` | `AdmsTecnico` |

---

### 2. Diretórios de Views Renomeados

| Diretório Antigo | Diretório Novo |
|---|---|
| `app/adms/Views/mecanico/` | `app/adms/Views/tecnico/` |
| `app/adms/Views/entradaVeiculo/` | `app/adms/Views/entradaEquipamento/` |
| `app/adms/Views/relatorio/mecanico/` | `app/adms/Views/relatorio/tecnico/` |

#### Ficheiros de View Renomeados
| Ficheiro Antigo | Ficheiro Novo |
|---|---|
| `Views/mecanico/pgMecanico.php` | `Views/tecnico/pgTecnico.php` |
| `Views/entradaVeiculo/pgEntradaVeiulo.php` | `Views/entradaEquipamento/pgEntradaEquipamento.php` |
| `Views/cliente/pgVeiculo.php` | `Views/cliente/pgEquipamento.php` |
| `Views/consulta/pgVeiculo.php` | `Views/consulta/pgEquipamento.php` |
| `Views/relatorio/imprimirVeiculo.php` | `Views/relatorio/imprimirEquipamento.php` |
| `Views/relatorio/relatorioVeiculo.php` | `Views/relatorio/relatorioEquipamento.php` |
| `Views/relatorio/tecnico/relatorioVeiculo.php` | `Views/relatorio/tecnico/relatorioEquipamento.php` |
| `Views/relatorio/tecnico/imprimirRelatorioVeiculo.php` | `Views/relatorio/tecnico/imprimirRelatorioEquipamento.php` |

---

### 3. Métodos PHP Renomeados

#### `AdmsTecnico.php` (antes `AdmsMecanico.php`)
| Método Antigo | Método Novo |
|---|---|
| `cdsMecanico()` | `cdsTecnico()` |
| `deleteMecanico()` | `deleteTecnico()` |
| `editMecanico()` | `editTecnico()` |
| `dadosMecanico()` | `dadosTecnico()` |
| `dadosMecanicos()` | `dadosTecnicos()` |
| `valMecanicos()` | `valTecnicos()` |
| `valEditMecananicos()` *(typo corrigido)* | `valEditTecnicos()` |
| `idMecanico()` | `idTecnico()` |
| `entradaVeiculo()` | `entradaEquipamento()` |
| `dadosEntradaCarro()` | `dadosEntradaEquipamento()` |
| `deletVeiculo()` | `deletEntradaEquipamento()` |
| `valOrcClienteVeiculo()` | `valOrcClienteEquipamento()` |
| `valEditOrcClienteVeiculo()` | `valEditOrcClienteEquipamento()` |

---

### 4. Rotas URL Renomeadas

| Rota Antiga (`?url=`) | Rota Nova (`?url=`) | Controller |
|---|---|---|
| `mecanico` | `tecnico` | `Tecnico` |
| `veiculo` | `equipamento` | `Equipamento` |
| `entradaVeiculo` | `entradaEquipamento` | `EntradaEquipamento` |
| `relatorioMecanica` | `relatorioTecnico` | `RelatorioTecnico` |

---

### 5. Base de Dados — Tabelas

| Tabela Antiga | Tabela Nova |
|---|---|
| `mecanicos` | `tecnicos` |

#### Colunas Renomeadas
| Tabela | Coluna Antiga | Coluna Nova |
|---|---|---|
| `tecnicos` (ex `mecanicos`) | `idmecanicos` | `idtecnico` |
| `orcamentos` | `mecanico` | `tecnico` |
| `conntas_areceber` | `mecanico` | `tecnico` |
| `comissao` | `nifmecanico` | `niftecnico` |
| `entrada_equipamento` | `nifmecanico` | `niftecnico` |
| `entrada_veiculo` | `nifmecanico` | `niftecnico` |

#### Valor Padrão da Coluna `usuario.nivel`
| Antes | Depois |
|---|---|
| `DEFAULT 'mecanico'` | `DEFAULT 'tecnico'` |

---

### 6. Constantes Renomeadas (`core/ConfigController.php`)

| Constante Antiga | Constante Nova |
|---|---|
| `COMISSAO_MECANICO` | `COMISSAO_TECNICO` |
| `MECHANIC_COMMISSION` (chave .env) | `TECHNICIAN_COMMISSION` |

---

### 7. Views da Base de Dados Atualizadas

| View | Alteração |
|---|---|
| `dadosorcamento` | `o.mecanico` → `o.tecnico`; alias `mecanico` mantido para compatibilidade |
| `dadosOrcamentosCompletoComProdutos` | `o.mecanico` → `o.tecnico`; alias mantido |
| `compras_contaspagar_dadosproduto` | View nova — une compras, contas_apagar, produto, categoria, fornecedor |

---

### 8. O Que NÃO Foi Alterado (Intencionalmente)

| Elemento | Motivo |
|---|---|
| Coluna `orcamentos.veiculo` | Legado — armazena `numero_serie`; renomear requer migração de dados complexa |
| Tabela `veiculo` | Mantida como alias de compatibilidade |
| Tabela `entrada_veiculo` | Mantida como alias de compatibilidade |
| Alias `mecanico` nas views | Compatibilidade com código legado que ainda lê esse campo |

---

### 9. Como Migrar uma Base de Dados Existente

```sql
-- Execute no phpMyAdmin ou linha de comando MySQL:
source /caminho/para/database/migrate_to_informatica.sql
```

---

## Alterações Anteriores — 15 de Junho de 2026

### Bugs Corrigidos

#### Entrada de Equipamento — `identrada_veiculo` inexistente

**Erro:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'identrada_veiculo'`

| Ficheiro | Linha | Alteração |
|----------|-------|-----------|
| `app/adms/Models/AdmsTecnico.php` | 2681 | `ORDER BY identrada_veiculo` → `ORDER BY id` |
| `app/adms/Models/AdmsTecnico.php` | 2690 | `WHERE identrada_veiculo=` → `WHERE id=` |

#### Estatísticas — `nome_servico` inexistente na view

| Ficheiro | Linhas | Alteração |
|----------|--------|-----------|
| `app/adms/Models/AdmsGraficos.php` | 353, 361, 372, 375 | `nome_servico` → `tipo_servico` |

#### Orçamento — Colunas erradas em listagem e relatório

| Ficheiro | Alteração |
|----------|-----------|
| `app/adms/Views/orcamento/pgOrcamento.php` | `nome`→`nome_cliente`, `valor_maodeobra`→`valor`, `nome_servico`→`tipo_servico` |
| `app/adms/Views/orcamento/pgServico.php` | Idem |

### Nova Funcionalidade — Recuperação de Senha

Fluxo completo de recuperação de senha via email com código temporário de 6 dígitos.

| Ficheiro | Descrição |
|----------|-----------|
| `app/adms/Models/AdmsRecuperarSenha.php` | Lógica de geração e validação de código |
| `app/adms/Controllers/RecuperarSenha.php` | Controller de 3 passos |
| `app/adms/Views/recuperarSenha/pgEmail.php` | Passo 1: email |
| `app/adms/Views/recuperarSenha/pgCodigo.php` | Passo 2: código de 6 dígitos |
| `app/adms/Views/recuperarSenha/pgNovaSenha.php` | Passo 3: nova senha |
| `database/schema.sql` | Tabela `reset_senha` adicionada |
