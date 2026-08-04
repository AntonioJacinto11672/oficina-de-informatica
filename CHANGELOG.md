# CHANGELOG — Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos (Universidade Lusíada de Angola)

---

## Versão 5.0.1 — Correções pós-entrega e simplificação do fluxo de Ocorrências

**Data:** 2026-08-04
**Autor:** António Jacinto (com assistência de Claude)
**Descrição:** Três correções identificadas em utilização real do sistema já adaptado (v5.0.0).

### DataTables — aviso "Incorrect column count"

**Causa:** as linhas "Não existem dados para apresentar" usavam `<td colspan="N">` dentro do `<tbody>` — não suportado pelo DataTables (confirmado em [datatables.net/tn/18](https://datatables.net/tn/18), que recomenda explicitamente remover `colspan`/`rowspan` do corpo da tabela). Uma primeira tentativa de correção removeu a extensão *Responsive* do DataTables, o que não resolveu por si só a causa raiz.

**Correção definitiva:** removidas as linhas `<tr><td colspan="N">…</td></tr>` de "sem dados" nas 12 tabelas que as tinham (`pgUtilizador`, `pgDepartamento`, `pgEquipamento`, `pgTipoManutencao`, `pgCompras`, `pgFornecedor`, `pgPlaneamento`, `pgDiagnostico`, `pgAbatimento`, `pgHistoricoGeral`, `pgProduto`, `pgMovimentoEstoque`). O `<tbody>` fica vazio quando não há dados, e é o próprio DataTables a apresentar a mensagem "Não existem dados disponíveis nesta tabela" (`language.sEmptyTable`, já traduzida em `datatables-demo.js`).

### E-mail de credenciais do Técnico — falha silenciosa

**Causa:** `AdmsTecnico::enviarCredenciais()` definia uma mensagem de aviso quando o envio SMTP falhava (ex.: `SMTP_USER`/`SMTP_PASS` vazios no `.env`), mas `cdsTecnico()` escrevia por cima, sem condição, a mensagem de sucesso — escondendo a falha real do envio.

**Correção:** `enviarCredenciais()` passa a devolver `bool`; `cdsTecnico()` usa esse resultado para mostrar "Técnico registado com sucesso, mas o e-mail não foi enviado" (com a senha temporária incluída na mensagem, para comunicação manual) sempre que o envio falhar. A falha real (`SMTP Error: Could not authenticate`) passa a ficar registada em `error_log()` para diagnóstico.

### Fluxo de Ocorrências — categoria redundante e mudança de estado livre

**Problema 1:** o formulário de Ocorrência pedia a **Categoria** (Preventiva/Corretiva) em separado do **Tipo de Manutenção** (já classificado como Preventivo ou Corretivo no catálogo), permitindo combinações contraditórias (ex.: tipo "Corretiva" com categoria "Preventiva").

**Correção:** o campo Categoria foi removido do formulário de Ocorrência. Uma ocorrência aberta manualmente é sempre gravada como **Corretiva** (`AdmsOcorrencia::cdsOcorrencia()`); a categoria deixou de ser editável (`editOcorrencia()`). Manutenção **Preventiva** só nasce automaticamente a partir de um Plano de Manutenção Preventiva vencido (já assim desde a v5.0.0). O dropdown de Tipo de Manutenção passa a filtrar pelo catálogo — `AdmsOcorrencia::dadosTiposManutencao(?string $categoria)` — mostrando só tipos Corretivos nas Ocorrências e só tipos Preventivos no Planeamento.

**Problema 2:** existia um ícone "Alterar Estado" na lista de Ocorrências que abria um menu com **qualquer** estado à escolha (Aberta, Em diagnóstico, Aguardando execução, Em execução, Concluída, Cancelada), permitindo saltar o fluxo real.

**Correção:** o modal e o botão "Alterar Estado" foram removidos (view `pgOcorrencia.php`, controller `Ocorrencia.php`). O estado passa a avançar **apenas** como consequência das ações reais já existentes — registar Diagnóstico, Encaminhar para Execução, Iniciar Execução, Encerrar — sem alterar o método interno `AdmsOcorrencia::alterarEstado()`, que continua a ser usado por essas ações. O botão **Encerrar**, no ecrã de Execução, já cobre a necessidade de o técnico terminar o trabalho que iniciou.

### Documentação

`MANUAL_UTILIZACAO.md` reorganizado com índice de navegação no topo e atualizado para refletir o fluxo de Ocorrências sem escolha de categoria e sem "Alterar Estado"; nova entrada na secção de Perguntas Frequentes sobre a mensagem de e-mail não enviado.

---

## Versão 5.0.0 — Adaptação Institucional para a Universidade Lusíada de Angola (TFC)

**Data:** 2026-08-04
**Autor:** António Jacinto (com assistência de Claude)
**Descrição:** Transformação completa do sistema, de uma oficina comercial de assistência técnica informática (com clientes, orçamentos, vendas, comissões e contas a pagar/receber) para uma solução **institucional interna** do Departamento de TI da Universidade Lusíada de Angola, mantendo integralmente a arquitetura Cliente-Servidor + MVC. Auditoria completa a todas as áreas do sistema (base de dados, backend, frontend, permissões, sessões, autenticação, DataTables, SQL, rotas, uploads, mensagens, e-mails, relatórios), com correção de todos os erros encontrados. Ver `RELATORIO_TFC_ADAPTACAO.md` para o detalhe completo da auditoria, correções e justificações.

### Base de dados

`database/schema.sql` reescrito de raiz como fonte de verdade única (v5.0.0), substituindo o encadeamento de migrações incrementais das versões anteriores. Removidas por completo as tabelas comerciais: `clientes`, `orcamentos`, `orc_prod`, `vendas`, `comissao`, `comissao_config`, `contas_apagar`, `conntas_areceber`, `movimentacao`, `tipo_servico`, `entrada_equipamento`, `entrada_veiculo`, `veiculo`, `recepcionista`. Novas tabelas: `departamentos`, `tipo_manutencao`. `equipamento` perde `idcliente` e ganha `iddepartamento`, `idresponsavel`, `idfornecedor`, `garantia_ate`; `estado` passa a `ENUM('Disponível','Em Manutenção','Avariado','Abatido')`. `ocorrencias` perde `id_orcamento`, `tecnico` (NIF livre) e a coluna `status` duplicada; ganha `categoria_manutencao` e `id_tipo_manutencao`. `execucao_manutencao` perde `id_orcamento`. `produto` perde `valor_venda`; ganha `estoque_minimo`. `compras` reconstruída sem ligação a `contas_apagar`. `usuario.nivel` passa a `ENUM('gerente','tecnico')`, corrigindo o erro ortográfico persistente `'adimin'`.

### Autenticação, sessões e permissões

- `AdmsLogin.php`: senhas migradas de MD5 para `password_hash()`/`password_verify()`; removido `var_dump()` que expunha os dados do utilizador (incluindo hash da senha) no ecrã de login.
- `core/ConfigController.php`: removido o auto-seed do utilizador admin que corria em **cada pedido HTTP**; o utilizador Gerente inicial passa a ser criado apenas por `database/schema.sql`.
- `Sair.php`: logout passa a usar `session_unset()` + `session_destroy()` + limpeza do cookie de sessão (antes: `unset()` seletivo de variáveis).
- `core/Permissao.php`: acrescentada verificação de acesso por papel — rotas exclusivas do Gerente (Utilizadores, Técnicos, Departamentos, Fornecedores, Peças, Stock, Relatórios, Configurações, etc.) deixam de poder ser acedidas por um Técnico via URL direta (antes só se validava se havia sessão iniciada, sem distinção de papel).
- `Login.php` (novo `session_regenerate_id(true)` no login bem-sucedido, mitigação de fixação de sessão).

### Módulos comerciais removidos

Controllers, Models e Views eliminados: `Cliente`, `Vendas`, `Comissoes`, `ComissaoConfig`, `ContasPagar`, `ContaReceber`, `Movimentacao`, `Orcamento`, `OrcamentoRecepcao`, `AddProdutoOrcamento`, `Servico`, `TipoServico`, `Recepcionista`, `EntradaEquipamento`, `Dashboard` (relatórios comerciais), `Consultas`, `Chat` (template estático não funcional da AdminLTE), `RelatorioTecnico`, `AdmsRecepcionista` (modelo "deus" que misturava Clientes/Equipamentos/Contas), `AdmsMpdf` (stub morto).

### Novos módulos institucionais

`Utilizador` (gestão de contas), `Departamento`, `TipoManutencao`, `Historico` (ocorrências concluídas/canceladas), além da reconstrução completa de `Equipamento`, `Fornecedor`, `Produto`, `Categoria` e `Compras` com modelos dedicados (`AdmsEquipamento`, `AdmsFornecedor`, `AdmsProduto`, `AdmsCategoria`, `AdmsCompras`, `AdmsDepartamento`, `AdmsTipoManutencao`, `AdmsUtilizador`), substituindo a lógica que antes vivia dispersa em `AdmsTecnico`/`AdmsRecepcionista`.

### Fluxo de manutenção

O fluxo passa a ser exclusivamente `Ocorrência → Diagnóstico → Execução → Conclusão → Histórico`, sem qualquer ligação a orçamentos. Estados de ocorrência simplificados para `Aberta → Em diagnóstico → Aguardando execução → Em execução → Concluída` (ou `Cancelada`). O início/fim de uma execução passa a atualizar automaticamente `equipamento.estado` (`Em Manutenção` / `Disponível`).

### Menus e dashboards

`app/adms/Views/include/dashboard.php` reescrito para dois papéis (Gerente/Técnico), seguindo a estrutura de menu institucional (Cadastros, Manutenção, Stock, Relatórios, Configurações para o Gerente; módulos de manutenção + Perfil para o Técnico). `AdmsHome.php`/`home.php` reescritos com os indicadores institucionais pedidos (Total de Equipamentos, Técnicos, Ocorrências, Diagnósticos, Execuções, Manutenções Preventivas/Corretivas, Equipamentos em Manutenção/Disponíveis, Fornecedores, Compras, Stock Baixo para o Gerente; Ocorrências Atribuídas, Diagnósticos Pendentes, Manutenções em Execução/Concluídas/Pendentes, Planeamentos, Equipamentos em Manutenção para o Técnico) — sem qualquer indicador financeiro. `AdmsGraficos.php` reescrito de gráficos de movimentação de caixa para estatísticas de manutenção (ocorrências por mês, preventiva vs. corretiva, equipamentos por estado, peças mais utilizadas).

### Mensagens e e-mails

Revisão de todas as mensagens do sistema para português correto e vocabulário institucional (sem "Cliente", "Comissão", "Oficina", etc.); corrigido mojibake UTF-8 concentrado nos módulos comerciais entretanto removidos. E-mails (recuperação de senha, credenciais de novo técnico) unificados para usarem sempre `Core\Config` (sem host/credenciais SMTP fixas no código nem remetente pessoal "hardcoded"), com `CharSet = 'UTF-8'` e o nome institucional completo do sistema.

### Relatórios

`Relatorio.php`/`AdmsRelatorio.php` reescritos: Equipamentos, Técnicos, Ocorrências, Diagnósticos, Manutenções, Planeamentos Preventivos, Histórico, Fornecedores, Stock e Compras, com impressão (via browser) e exportação CSV (`fputcsv` nativo, com BOM UTF-8). Removidos os relatórios comerciais (Comissões, Contas a Pagar/Receber, Vendas, Movimentação) e o stub morto de mPDF.

### Correções técnicas transversais

- DataTables: inicialização global com idioma português e extensão *Responsive*; corrigido `colspan` fixo incorreto em `pgAbatimento.php`.
- SQL: consultas remanescentes com concatenação direta de variáveis substituídas por `bindParam`/placeholders.
- PHP 8.2: parâmetros `nullable` implícitos tornados explícitos (`?array`, `?int`, `?string`); `Conn.php` passa a declarar `charset=utf8mb4` explicitamente na DSN PDO.
- Uploads: validação de tipo real do ficheiro (`finfo`) além da extensão, limite de 5 MB, e verificação `is_uploaded_file()`.
- Testado em runtime (login, CRUD de todos os módulos, fluxo completo Ocorrência→Diagnóstico→Execução→Histórico, controlo de acesso por papel, relatórios e exportação CSV) sem avisos/erros PHP.

---

## Versão 4.0.0 — Sistema CMMS: Ocorrências, Diagnóstico, Execução, Planeamento Preventivo, Abatimento e Comissões Configuráveis

**Data:** 2026-07-09
**Autor:** Josimar Ferreira (com assistência de Claude)
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
**Autor:** Josimar Ferreira

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
**Autor:** Josimar Ferreira

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
**Autor:** Josimar Ferreira
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
