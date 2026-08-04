# Manual de Utilização — Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos (Universidade Lusíada de Angola)

Guia passo-a-passo de utilização do sistema depois de instalado. Este é um sistema **institucional interno** do Departamento de TI — não existem clientes externos, vendas, orçamentos comerciais ou comissões. Para instalar pela primeira vez, siga o **[README.md](README.md#instalação-passo-a-passo)** ou o **[GUIA_EXECUCAO.md](GUIA_EXECUCAO.md)**.

## Índice

1. [Acesso ao Sistema](#1-acesso-ao-sistema)
2. [Fluxo de Manutenção](#2-fluxo-de-manutenção)
3. [Módulos de Manutenção](#3-módulos-de-manutenção)
   - [3.1 Ocorrências](#31-ocorrências)
   - [3.2 Diagnóstico Técnico](#32-diagnóstico-técnico)
   - [3.3 Execução da Manutenção](#33-execução-da-manutenção)
   - [3.4 Planeamento de Manutenção Preventiva](#34-planeamento-de-manutenção-preventiva)
   - [3.5 Histórico](#35-histórico)
   - [3.6 Abatimento de Equipamentos](#36-abatimento-de-equipamentos)
4. [Cadastros e Stock (Gerente)](#4-cadastros-e-stock-gerente)
5. [Relatórios e Estatísticas (Gerente)](#5-relatórios-e-estatísticas-gerente)
6. [Configurações (Gerente)](#6-configurações-gerente)
7. [Guia por Papel de Acesso](#7-guia-por-papel-de-acesso)
8. [Perguntas Frequentes](#8-perguntas-frequentes)
9. [Referência Rápida de URLs](#9-referência-rápida-de-urls)

---

## 1. Acesso ao Sistema

### 1.1 Login

1. Aceda a `http://localhost/oficina-de-informatica/` no browser.
2. Introduza o **e-mail** e a **senha**.
3. Clique em **Acessar**.

| Papel | E-mail padrão | Senha padrão |
|-------|----------------|---------------|
| Gerente de TI | `gerente.ti@ula.co.ao` | `Lusiada@2026` |

> Altere a senha padrão imediatamente após o primeiro acesso. Depois do login, o sistema mostra um menu lateral diferente consoante o **papel** da conta: **Gerente** (acesso total) ou **Técnico** (módulos de manutenção).

### 1.2 Esqueci-me da senha

1. Na página de login, clique em **Esqueci a minha senha**.
2. Introduza o e-mail da conta — é enviado um código de 6 dígitos por e-mail (válido 30 minutos, requer `SMTP_*` configurado no `.env`).
3. Introduza o código recebido.
4. Defina uma nova senha.

### 1.3 Perfil e sessão

- **Editar Perfil** (menu superior direito, junto do seu nome): atualizar nome, contacto e dados pessoais.
- **Terminar Sessão**: termina a sessão em segurança. Use sempre esta opção em computadores partilhados.

---

## 2. Fluxo de Manutenção

O sistema segue um fluxo único, guiado sempre pelas mesmas ações — **não existe um menu para "saltar" de estado livremente**: cada estado só avança quando se completa o passo correspondente.

```
Ocorrência → Diagnóstico → Execução da Manutenção → Conclusão → Histórico
```

| Passo | Ação | Quem | Efeito |
|---|---|---|---|
| 1 | Abrir **Ocorrência** para um ou mais equipamentos | Gerente ou Técnico | Nasce no estado **Aberta**, sempre como manutenção **Corretiva** |
| 2 | Registar **Diagnóstico** (problema, solução proposta, peças necessárias) | Técnico | Ocorrência avança automaticamente para **Em diagnóstico** |
| 3 | Clicar em **Encaminhar para Execução** | Técnico | Ocorrência passa a **Aguardando execução**; o diagnóstico fica bloqueado para edição (preserva o histórico) |
| 4 | Clicar em **Iniciar Execução** | Técnico | Ocorrência passa a **Em execução**; o equipamento fica automaticamente **Em Manutenção** |
| 5 | Registar as peças usadas e clicar em **Encerrar** | Técnico | Ocorrência passa a **Concluída**; equipamento volta a **Disponível**; registo passa a constar do **Histórico** |

> **Nota sobre a categoria da manutenção:** uma Ocorrência aberta manualmente é **sempre Corretiva** — não há campo para escolher. A manutenção **Preventiva** só é gerada automaticamente pelo módulo de **Planeamento Preventivo** (secção [3.4](#34-planeamento-de-manutenção-preventiva)), evitando que se marque uma ocorrência como preventiva sem existir um plano por trás.

Todo o percurso fica registado no **Histórico da Ocorrência** (ícone de relógio na lista de Ocorrências), com data, utilizador responsável e observação de cada mudança de estado.

---

## 3. Módulos de Manutenção

### 3.1 Ocorrências

Menu **Manutenção › Ocorrências** (Gerente e Técnico).

1. Clique em **Nova Ocorrência**.
2. Selecione **um ou vários equipamentos** (Ctrl+clique para selecionar mais do que um).
3. Escolha o **Tipo de Manutenção** (do catálogo, já filtrado para os tipos corretivos), a **Prioridade**, a **Data Prevista** e, se souber já, o **Técnico Responsável**.
4. Descreva o problema/pedido e clique em **Abrir Ocorrência**.
5. Na lista, cada ocorrência tem ícones de ação: **Histórico**, **Diagnóstico**, **Execução**, **Editar**, **Atribuir Técnico** e **Eliminar**.

> O estado da ocorrência (Aberta, Em diagnóstico, Aguardando execução, Em execução, Concluída) **não é editável diretamente** — avança apenas através do Diagnóstico e da Execução (secções seguintes).

### 3.2 Diagnóstico Técnico

Acedido a partir do ícone de estetoscópio na lista de Ocorrências, ou pelo menu **Manutenção › Diagnósticos** (lista geral).

1. Na página da ocorrência, clique em **Novo Diagnóstico**.
2. Escolha o equipamento (se a ocorrência tiver mais do que um), descreva o **Problema Encontrado**, a **Solução Proposta** e as **Peças Solicitadas**.
3. Grave — a ocorrência avança automaticamente para **Em diagnóstico**.
4. Pode editar o diagnóstico enquanto não for encaminhado.
5. Quando estiver pronto, clique em **Encaminhar para Execução**.

### 3.3 Execução da Manutenção

Menu **Manutenção › Execuções**, ou pelo ícone na lista de Ocorrências.

1. Clique em **Iniciar Execução** — a ocorrência passa a **Em execução** e o equipamento fica **Em Manutenção**.
2. Adicione as **peças efetivamente usadas** — o stock é reduzido automaticamente e fica registado no histórico de movimentações (secção [4.4](#44-histórico-de-movimentações-de-stock)).
3. Pode remover uma peça adicionada por engano — o stock é reposto.
4. Quando o trabalho estiver terminado, clique em **Encerrar** — é o próprio técnico que iniciou (ou qualquer técnico atribuído) quem termina o trabalho a partir daqui. A ocorrência passa a **Concluída** e o equipamento volta a **Disponível**.

### 3.4 Planeamento de Manutenção Preventiva

Menu **Manutenção › Planeamento Preventivo** (Gerente e Técnico).

1. Clique em **Novo Plano**: escolha o equipamento, o tipo de manutenção (do catálogo, já filtrado para os tipos preventivos), o técnico responsável, a **periodicidade em dias** (ex.: 90 para trimestral) e a data de início.
2. Quando a **Próxima Execução** de um plano chega à data (ou já passou), a linha fica marcada como **Vencido** e aparece o botão **Gerar Ocorrências Pendentes**.
3. Ao clicar, o sistema cria automaticamente uma Ocorrência **Preventiva** para cada plano vencido, e avança a "próxima execução" pela periodicidade definida. Esta é a **única forma** de uma ocorrência nascer como Preventiva.
4. Pode **Ativar/Inativar** um plano sem o eliminar, ou editá-lo.
5. Planos a vencer nos próximos 30 dias aparecem também no **Dashboard do Gerente**.

> Em produção, a geração de ocorrências preventivas pode ser automatizada através do script `cron_planeamento.php`, agendado nas Tarefas Agendadas do Windows ou no `cron` do Linux.

### 3.5 Histórico

Menu **Manutenção › Histórico** (Gerente e Técnico) — lista todas as ocorrências **Concluídas**, com o respetivo equipamento, categoria, técnico e data de encerramento. Cada linha tem uma ligação para o detalhe completo da ocorrência.

### 3.6 Abatimento de Equipamentos

Menu **Manutenção › Abatimento de Equipamentos** (Gerente aprova; qualquer utilizador autenticado pode solicitar via URL).

1. Clique em **Solicitar Abatimento**, escolha o equipamento e descreva o **Motivo**.
2. O pedido fica **Solicitado**, à espera de decisão do Gerente.
3. O Gerente pode **Aprovar** (o equipamento passa a estado **Abatido**) ou **Rejeitar**.

---

## 4. Cadastros e Stock (Gerente)

### 4.1 Utilizadores, Técnicos e Departamentos

- **Utilizadores** (`/utilizador`): lista todas as contas, permite ativar/desativar e alterar o papel (Gerente/Técnico).
- **Técnicos** (`/tecnico`): CRUD da ficha do técnico. Ao cadastrar um novo técnico, o sistema cria automaticamente a conta de acesso (papel `tecnico`) com uma senha temporária enviada por e-mail — **se o envio falhar** (ex.: SMTP não configurado), o sistema avisa claramente e mostra a senha temporária no ecrã, para ser comunicada ao técnico por outro meio (ver [FAQ](#8-perguntas-frequentes)).
- **Departamentos** (`/departamento`): departamentos da Universidade a quem os equipamentos pertencem.

### 4.2 Equipamentos e Fornecedores

- **Equipamentos** (`/equipamento`): código patrimonial, património, nome, tipo, marca, modelo, número de série (único), estado (Disponível/Em Manutenção/Avariado/Abatido), localização, departamento, responsável, fornecedor, data de aquisição e garantia. O ícone de histórico mostra todas as manutenções desse equipamento.
- **Fornecedores** (`/fornecedor`): fornecem equipamentos, peças e consumíveis à Universidade — sem qualquer funcionalidade comercial.
- **Tipos de Manutenção** (`/tipoManutencao`): catálogo técnico, cada tipo marcado como **Preventiva** ou **Corretiva**. As Ocorrências só mostram os tipos Corretivos; o Planeamento Preventivo só mostra os tipos Preventivos.
- **Categorias de Equipamento** (`/categoriaEquipamento`): tipos de equipamento (Computador, Impressora, Servidor, etc.).
- **Peças e Consumíveis** (`/produto` e `/categoria`): stock interno, com stock mínimo por peça.

### 4.3 Stock

- **Entradas** (`/movimentoEstoque?tipo=Entrada`) e **Saídas** (`/movimentoEstoque?tipo=Saida`): ledger de todas as movimentações de stock.
- **Compras** (`/compras`): registo de aquisição de peças/consumíveis junto de um fornecedor — atualiza o stock automaticamente.
- **Stock Baixo** (`/estoque`): peças abaixo do stock mínimo definido; permite adicionar stock diretamente.

### 4.4 Histórico de Movimentações de Stock

Ícone de histórico na lista de **Peças e Consumíveis**, ou diretamente em `/movimentoEstoque?produto=<id>`. Mostra todas as entradas e saídas de stock de uma peça — compras, peças usadas em execuções, devoluções — com data, quantidade, origem e utilizador responsável.

---

## 5. Relatórios e Estatísticas (Gerente)

Menu **Relatórios** (`/relatorio`): Equipamentos, Técnicos, Ocorrências, Diagnósticos, Manutenções (Execuções), Planeamentos Preventivos, Histórico, Fornecedores, Stock e Compras. Cada relatório pode ser **impresso** (botão Imprimir, usa a função de impressão do browser — "Guardar como PDF" está disponível na maioria dos browsers) ou **exportado em CSV**.

Menu **Estatísticas** (`/graficos`): ocorrências por mês, preventiva vs. corretiva, equipamentos por estado e peças mais utilizadas.

---

## 6. Configurações (Gerente)

Menu **Configurações** (`/configuracoes`): apresenta os dados institucionais em uso (nome do sistema, morada, contacto do Departamento de TI, limiar de stock baixo). Estas definições são geridas pelo ficheiro `.env` do servidor.

---

## 7. Guia por Papel de Acesso

### 7.1 Gerente

Acesso total. Menu lateral: **Dashboard**, **Cadastros** (Utilizadores, Técnicos, Departamentos, Equipamentos, Fornecedores, Tipos de Manutenção, Peças e Consumíveis), **Manutenção** (Ocorrências, Diagnósticos, Execuções, Planeamento Preventivo, Ordens de Manutenção, Histórico), **Stock** (Entradas, Saídas, Compras, Stock Baixo), **Relatórios**, **Configurações**.

Fluxo típico de preparação de um sistema novo:
1. Cadastrar **Departamentos**.
2. Cadastrar **Categorias de Equipamento** e **Tipos de Manutenção** (marcando cada um como Preventiva ou Corretiva).
3. Cadastrar **Fornecedores** e **Categorias de Peças**.
4. Cadastrar **Técnicos** — cada um recebe conta de acesso automaticamente.
5. Cadastrar os **Equipamentos** existentes na instituição.
6. A partir daqui, o dia-a-dia é sobretudo supervisão: acompanhar o dashboard, aprovar abatimentos, consultar relatórios.

### 7.2 Técnico

Foco na execução das manutenções atribuídas. Menu lateral: **Dashboard**, **Ocorrências**, **Diagnósticos**, **Execuções**, **Planeamento Preventivo**, **Equipamentos**, **Histórico**, **Perfil**.

Fluxo típico:
1. **Ocorrências** — encontrar a ocorrência atribuída (ou atribuir-se a si próprio via **Atribuir Técnico**).
2. Registar o **Diagnóstico** e encaminhar para execução.
3. **Iniciar Execução**, registar as peças usadas.
4. **Encerrar** a execução quando o trabalho estiver concluído — a ocorrência fica concluída e passa a constar do Histórico.

---

## 8. Perguntas Frequentes

| Situação | O que fazer |
|----------|-------------|
| "Técnico registado, mas o e-mail não foi enviado" | O sistema confirma sempre que os dados foram gravados, mesmo que o e-mail falhe — a mensagem mostra a senha temporária para a comunicar manualmente. Configure `SMTP_HOST`/`SMTP_USER`/`SMTP_PASS`/`SMTP_PORT` no `.env` para o envio funcionar |
| Não recebo o e-mail com o código de recuperação de senha | Verifique as credenciais `SMTP_*` no `.env` |
| E-mail ou NIF "já existente" ao cadastrar | Cada e-mail/NIF/BI só pode estar associado a uma conta — verifique se a pessoa já está cadastrada |
| Não consigo abrir uma Ocorrência | Confirme que selecionou pelo menos um equipamento — é o único campo obrigatório além da descrição |
| Não vejo campo para escolher "Preventiva" ao abrir uma Ocorrência | É intencional — ocorrências manuais são sempre Corretivas; use o [Planeamento Preventivo](#34-planeamento-de-manutenção-preventiva) para manutenção preventiva |
| Diagnóstico não deixa editar | Diagnósticos já **encaminhados para execução** ficam bloqueados, para preservar o histórico |
| Peça não aparece disponível para adicionar à Execução | Confirme que tem stock (`estoque >= 1`) — o sistema não permite reduzir stock abaixo de zero |
| Equipamento não aparece na lista para nova Ocorrência | Equipamentos com estado **Abatido** ficam excluídos dessas listas |
| Acesso negado a uma página | Essa área está reservada ao Gerente de TI — confirme o papel da sua conta |
| Aviso do DataTables sobre "Incorrect column count" | Já corrigido — as tabelas vazias já não têm linhas com `colspan`; se voltar a aparecer nalguma página, reporte qual |
| Erros técnicos, instalação ou configuração | Consulte a secção **Resolução de Problemas** no [README.md](README.md#resolução-de-problemas) |

---

## 9. Referência Rápida de URLs

| Módulo | URL relativa |
|--------|--------------|
| Login | `/login` |
| Dashboard | `/home` |
| Utilizadores | `/utilizador` |
| Técnicos | `/tecnico` |
| Departamentos | `/departamento` |
| Equipamentos | `/equipamento` (`?historico=<id>` para o histórico) |
| Fornecedores | `/fornecedor` |
| Tipos de Manutenção | `/tipoManutencao` |
| Categorias de Equipamento | `/categoriaEquipamento` |
| Categorias de Peças | `/categoria` |
| Peças e Consumíveis | `/produto` |
| Ocorrências | `/ocorrencia` (`?historico=<id>` para o detalhe) |
| Diagnósticos | `/diagnostico` (`?ocorrencia=<id>`) |
| Execuções | `/execucao` (`?ocorrencia=<id>`) |
| Planeamento Preventivo | `/planeamento` |
| Histórico | `/historico` |
| Abatimento | `/abatimento` |
| Entradas de Stock | `/movimentoEstoque?tipo=Entrada` |
| Saídas de Stock | `/movimentoEstoque?tipo=Saida` |
| Histórico de Stock por Peça | `/movimentoEstoque?produto=<id>` |
| Compras | `/compras` |
| Stock Baixo | `/estoque` |
| Relatórios | `/relatorio?tipo=<tipo>` (`&export=csv` para CSV) |
| Estatísticas | `/graficos` |
| Configurações | `/configuracoes` |
| Perfil | `/perfil` |
| Sair | `/sair` |

---

*Ver também: [README.md](README.md) (instalação), [GUIA_EXECUCAO.md](GUIA_EXECUCAO.md) (execução rápida), [CHANGELOG.md](CHANGELOG.md) (histórico de alterações), [RELATORIO_TFC_ADAPTACAO.md](RELATORIO_TFC_ADAPTACAO.md) (relatório completo da adaptação institucional).*
