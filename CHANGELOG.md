# CHANGELOG — Sistema de Gestão de Assistência Técnica Informática

---

## Versão 4.0.0 — Sistema CMMS: Ocorrências, Diagnóstico, Execução, Planeamento Preventivo, Abatimento e Comissões Configuráveis

**Data:** 2026-07-09
**Autor:** António Jacinto (com assistência de Claude)
**Descrição:** Refactoração faseada do sistema de gestão de orçamentos de oficina para um CMMS
(Computerized Maintenance Management System) completo, alinhado com os 17 módulos de gestão de
manutenção preventiva e corretiva. `ocorrencias` — que existia apenas como tabela-sombra
sincronizada a partir de `orcamentos` — foi promovida a entidade central e independente do
sistema, com máquina de estados própria. O layout visual não foi alterado; todos os ecrãs novos
reutilizam o mesmo template (Bootstrap 4 / SB Admin 2) dos ecrãs existentes. Entregue em 6 fases,
cada uma testada end-to-end contra a base de dados de desenvolvimento antes de avançar.

### Nova convenção de migrações

Introduzido um sistema de migrações versionado, substituindo os scripts descartáveis (`migrar.php`)
usados até à versão 3.0.2:

| Ficheiro | Descrição |
|---|---|
| `migrate.php` | Runner permanente — aplica os ficheiros pendentes de `database/migrations/*.php` por ordem, regista cada um na tabela `schema_migrations`, nunca repete uma migração já aplicada |
| `database/migrations/0001` a `0010` | Dez migrações desta versão (ver detalhe por fase abaixo) |
| `database/schema.sql` | Actualizado como fonte de verdade para instalações de raiz (inclui já todas as tabelas/colunas novas) |

### Fase 0 — Infra-estrutura de base

| Item | Descrição |
|---|---|
| `core/ConfigController.php` | Removido o `CREATE TABLE IF NOT EXISTS ocorrencias` que corria em **cada pedido HTTP** — DDL deixa de correr por pedido, `schema.sql`/migrações passam a ser a única fonte de verdade |
| `tecnicos.idusuario`, `recepcionista.idusuario` | Novas colunas com FK real para `usuario.idusuario` (antes o cruzamento entre conta de login e ficha de técnico/recepcionista era feito por NIF, sem integridade referencial) |
| Sidebar | Rótulo "Categorias" → "Categorias de Peças" (clareza, sem alterar tabela/rota) |

### Fase 1 — Ocorrências (módulo 9, o módulo central)

| Item | Descrição |
|---|---|
| `ocorrencias` | Novas colunas `prioridade` (Baixa/Média/Alta/Urgente) e `idtecnico_responsavel` (FK `usuario`) |
| `ocorrencia_equipamento` | Nova tabela de junção — uma ocorrência pode agora ter **vários equipamentos** associados |
| `ocorrencia_historico` | Nova tabela de auditoria — regista cada mudança de estado, quem a fez e quando |
| `app/adms/Models/AdmsOcorrencia.php` | Novo Model dedicado — CRUD completo, atribuição de técnico, máquina de estados (`Aberta → Em diagnóstico → Aguardando orçamento → Aguardando aprovação → Em manutenção → Concluída`, ou `Cancelada`) |
| `app/adms/Controllers/Ocorrencia.php` + `Views/ocorrencia/` | Novo módulo — lista, criar/editar, atribuir técnico, alterar estado, histórico |
| `AdmsTecnico::criarOcorrencia()/atualizarOcorrencia()` | Passaram a wrappers finos que delegam para `AdmsOcorrencia` — comportamento do fluxo de orçamentos antigo preservado, ~110 linhas retiradas do modelo `AdmsTecnico` |

### Fase 2 — Diagnóstico Técnico (módulo 10)

| Item | Descrição |
|---|---|
| `diagnostico` | Nova tabela — histórico de diagnósticos ligado a uma ocorrência (problema, solução proposta, peças solicitadas, encaminhado_orcamento) |
| `app/adms/Models/AdmsDiagnostico.php` + `Controllers/Diagnostico.php` + `Views/diagnostico/` | Novo módulo — registar diagnóstico avança a ocorrência para "Em diagnóstico"; "Encaminhar para Orçamento" avança para "Aguardando orçamento" e bloqueia edição |

### Fase 3 — Orçamentos e Execução sob a Ocorrência (módulos 11-12)

| Item | Descrição |
|---|---|
| `orcamentos.id_ocorrencia` | Nova coluna (FK nullable) — liga o orçamento à ocorrência de origem, sem quebrar orçamentos legados (ficam `NULL`) |
| `execucao_manutencao`, `execucao_peca` | Novas tabelas — Execução da Manutenção como entidade própria, em paralelo ao fluxo antigo de "Serviço" (que se manteve intacto, por ser o caminho de maior tráfego do sistema) |
| `AdmsTecnico::abrirOrcamento()` | Passa a aceitar `id_ocorrencia` do formulário: se vier de uma Ocorrência, liga-se a ela em vez de criar uma ocorrência-sombra duplicada; se não vier, mantém o comportamento antigo (criação automática) |
| `app/adms/Models/AdmsExecucao.php` + `Controllers/Execucao.php` + `Views/execucao/` | Novo módulo — iniciar execução, adicionar/remover peças usadas (com actualização de stock), encerrar (fecha a ocorrência e marca o equipamento como "Concluído") |
| `Views/orcamento/pgOrcamento.php` | Pré-preenchimento do formulário "Novo Orçamento" quando aberto a partir de uma Ocorrência; corrigido um bug pré-existente em `Orcamento.php` onde `dadosOrcamento()` apagava qualquer pré-preenchimento do formulário |

### Fase 4 — Categorias de Equipamento e Equipamentos Abatidos (módulos 4, 14)

| Item | Descrição |
|---|---|
| `categoria_equipamento` | Nova tabela — substitui o texto livre `equipamento.tipo_equipamento`, seedada com 13 categorias (união dos valores já usados com os exemplos da especificação) |
| `equipamento` | Novas colunas: `codigo`, `patrimonio`, `nome`, `departamento`, `localizacao`, `idcategoria_equipamento`, `data_aquisicao`, `observacoes` |
| `dadosClienteEquipamento` (view) | Actualizada para expor os campos novos — `equipamento.nome` exposto como `nome_equipamento` para não colidir com `clientes.nome` |
| `equipamentos_abatidos` | Nova tabela — workflow de abatimento com aprovação (Solicitado → Aprovado/Rejeitado); aprovação marca `equipamento.estado='Abatido'` |
| `app/adms/Models/AdmsCategoriaEquipamento.php`, `AdmsAbatimento.php` + Controllers/Views | Dois novos módulos |

### Fase 5 — Planeamento de Manutenção Preventiva (módulo 13)

| Item | Descrição |
|---|---|
| `plano_manutencao_preventiva`, `plano_manutencao_lembrete` | Novas tabelas — periodicidade em dias por equipamento/serviço/técnico |
| `AdmsOcorrencia::criarOcorrenciaPreventiva()` | Novo método — cria uma ocorrência "Preventiva" a partir de um plano vencido |
| `app/adms/Models/AdmsPlaneamento.php` + `Controllers/Planeamento.php` + `Views/planeamento/` | Novo módulo — planos, botão "Gerar Ocorrências Pendentes" |
| `cron_planeamento.php` | Novo script permanente (não descartável) — gatilho para agendamento a nível de SO (Tarefas Agendadas / cron), já que o projecto não tem job runner próprio |
| `AdmsHome::dadosOcorrenciasPreventivas()` | Estendida (aditivamente, via `UNION ALL`) para mostrar também planos a vencer nos próximos 30 dias que ainda não geraram ocorrência |

### Fase 6 — Comissões Configuráveis e Histórico de Stock (módulos 15-16, lacuna do módulo 7)

| Item | Descrição |
|---|---|
| `comissao_config` | Nova tabela — percentagem de comissão por técnico e/ou tipo de serviço (prioridade: técnico+serviço > técnico > serviço > global > `.env VALOR_COMISSAO`), semeada com 1 linha global igual ao valor histórico |
| `comissao.percentual_aplicado` | Nova coluna — regista a % efectivamente usada em cada comissão (não depende da configuração actual, que pode mudar) |
| `movimento_estoque` | Nova tabela — ledger de entradas/saídas de stock, ligado aos **9 pontos** onde o código mexe em `produto.estoque` (compra, eliminar orçamento, adicionar/reduzir/remover peça de orçamento, estorno de compra cancelada, adicionar/remover peça de execução) |
| `dadosCustoOcorrencia` (view) | Nova — custo de peças + serviço por ocorrência |
| `app/adms/Models/AdmsComissaoConfig.php`, `AdmsMovimentoEstoque.php` + Controllers/Views | Dois novos módulos; `AdmsTecnico::aprovarOrcamento()` passou a consultar a configuração em vez da constante fixa `VALOR_COMISSAO` |
| `Controllers/MovimentoEstoque.php` + `Views/produto/pgMovimentoEstoque.php` | Novo ecrã de histórico de movimentações por produto, acessível a partir da lista de Produtos |

### Reforço de integridade referencial

Todas as relações **novas** introduzidas nesta versão têm FOREIGN KEY real (`ocorrencia_equipamento`,
`ocorrencia_historico`, `diagnostico`, `execucao_manutencao`, `execucao_peca`, `equipamentos_abatidos`,
`plano_manutencao_preventiva`, `comissao_config`, `movimento_estoque`, etc.). As relações antigas e
soltas (ex: `orcamentos.veiculo` como string) foram deixadas como estavam — normalizá-las é um
trabalho maior e mais arriscado, fora do âmbito desta versão.

### Documentação

| Ficheiro | Alteração |
|---|---|
| `MANUAL_UTILIZACAO.md` | Nova secção 3 "Módulos de Manutenção" (Ocorrências, Diagnóstico, Execução, Planeamento Preventivo, Categorias de Equipamento, Abatimento, Histórico de Stock, Configuração de Comissões); fluxo típico (secção 2) reescrito à volta da Ocorrência; referência de URLs actualizada |
| `Monografia_Assistencia_Tecnica_Informatica.docx` | Actualizada para reflectir a arquitectura CMMS (novos requisitos funcionais, diagrama ER, diagrama de classes, funcionalidades por perfil) |

---

## Versão 3.0.2 — Dados de Demonstração e Documentação de Utilização

**Data:** 2026-07-06
**Autor:** António Jacinto

### Adicionado

| Item | Descrição |
|------|-----------|
| `database/seed_dados_padrao.sql` | Script opcional e idempotente com conta de técnico de demonstração (`tecnico@gmail.com` / `tecnico123`) e dois tipos de serviço: "Manutenção Preventiva" e "Manutenção Corretiva" |
| `MANUAL_UTILIZACAO.md` | Novo manual de utilização passo-a-passo, organizado por perfil de acesso (Administrador, Recepcionista, Técnico) |
| `README.md` | Passo de instalação opcional para o seed de dados de demonstração; tabela de credenciais actualizada com a conta de técnico; ligação para o novo manual |
| `GUIA_EXECUCAO.md` | Passo de seed opcional e ligação para o novo manual de utilização |

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
