# Manual de Utilização — Sistema de Gestão de Manutenção Preventiva e Corretiva

Guia passo-a-passo de como usar o sistema depois de instalado. A partir da versão 4.0, o sistema
evoluiu de uma simples gestão de orçamentos de oficina para um CMMS (Computerized Maintenance
Management System) completo, com **Ocorrências** como ponto de entrada do fluxo de manutenção.
Para instalar o sistema pela primeira vez, siga o **[README.md](README.md#instalação-passo-a-passo)**
ou o **[GUIA_EXECUCAO.md](GUIA_EXECUCAO.md)**.

---

## 1. Acesso ao Sistema

### 1.1 Login

1. Aceda a `http://localhost/oficina-de-informatica/` no browser.
2. Introduza o **email** e a **senha**.
3. Clique em **Entrar**.

| Perfil | Email | Senha |
|--------|-------|-------|
| Administrador (Gerente) | `antjacinto11672@gmail.com` | `12345` |
| Técnico (opcional, demonstração) | `tecnico@gmail.com` | `tecnico123` |

> Depois do login, o sistema mostra menus diferentes consoante o **nível de acesso** da conta: `adimin` (Gerente), `tecnico` ou `recep` (Recepcionista).

### 1.2 Esqueci-me da senha

1. Na página de login, clique em **Esqueceu a senha?**.
2. Introduza o email da conta — é enviado um código de 6 dígitos por email (válido 30 minutos).
3. Introduza o código recebido.
4. Defina uma nova senha.

### 1.3 Perfil e sessão

- **Perfil** (menu superior direito, com o seu nome): editar dados pessoais e alterar a foto.
- **Sair**: termina a sessão em segurança. Use sempre esta opção em computadores partilhados.

---

## 2. Fluxo Típico de uma Manutenção

Desde a versão 4.0, a **Ocorrência** é o ponto de entrada real do sistema — é ela que representa o pedido de intervenção num equipamento, do início ao fim.

### 2.1 Visão Geral — Quem Faz o Quê, e Quando

```
 RECEPÇÃO                TÉCNICO                    RECEPÇÃO/GERENTE        TÉCNICO
 ─────────                ───────                    ────────────────        ───────
 1. Regista               3. Diagnostica     5. Orçamento    6. Aprova       7-8. Executa
    Cliente +      2. Abre    o problema         é criado       o Orçamento     e encerra
    Equipamento   Ocorrência                     a partir           │              │
       │              │            │             da Ocorrência       │              │
       ▼              ▼            ▼                  │              ▼              ▼
   [Cliente]      [Aberta] → [Em diagnóstico] → [Aguardando   → [Aguardando  → [Em manu-  → [Concluída]
   [Equipamento]                                 orçamento]      aprovação]     tenção]         │
                                                                                                  ▼
                                                                                    9. Comissão calculada
                                                                                    10. Pagamento + Entrega
```

Cada seta acima é um clique num botão do sistema (não é automático) — alguém tem sempre de o accionar. As secções 4.1 a 4.3 mostram exactamente onde clicar, passo a passo, para cada perfil.

Este é o percurso normal por extenso, do momento em que o problema é identificado até ao encerramento:

1. **Recepcionista** cadastra o **Cliente** (se ainda não existir) e o **Equipamento** associado (nº de série, categoria, marca, modelo, departamento, localização).
2. **Recepcionista** ou **Técnico** abre uma **Ocorrência**: escolhe um ou vários equipamentos, o tipo de serviço, a prioridade (Baixa/Média/Alta/Urgente), o tipo de manutenção (Corretiva/Preventiva) e, opcionalmente, atribui logo um técnico responsável. A ocorrência nasce no estado **Aberta**.
3. **Técnico** regista um **Diagnóstico** para a ocorrência: descreve o problema encontrado, a solução proposta e as peças necessárias. Ao gravar o primeiro diagnóstico, a ocorrência avança automaticamente para **Em diagnóstico**.
4. Quando o diagnóstico está concluído, o técnico clica em **Encaminhar para Orçamento** — a ocorrência passa a **Aguardando orçamento**.
5. A partir do ecrã da Ocorrência, cria-se o **Orçamento** correspondente (já vem com o cliente e o equipamento pré-preenchidos) — a ocorrência passa a **Aguardando aprovação**.
6. **Recepcionista** (ou Gerente) **aprova o Orçamento** junto do cliente — isto gera automaticamente uma **Conta a Receber** e actualiza a ocorrência para o estado correspondente.
7. **Técnico** clica em **Iniciar Execução** a partir da Ocorrência — o estado passa a **Em manutenção**. Regista aí as peças efectivamente usadas (o stock é actualizado automaticamente, com histórico).
8. Ao terminar, o técnico clica em **Encerrar Execução** — a ocorrência passa a **Concluída** e o(s) equipamento(s) ficam marcados como "Concluído".
9. O sistema calcula a **Comissão** do técnico automaticamente, usando a percentagem configurada para esse técnico/tipo de serviço (ou o valor global, se não houver configuração específica).
10. **Recepcionista/Gerente** regista o **pagamento** na Conta a Receber e marca o equipamento como entregue.

Todo este percurso fica registado no **Histórico da Ocorrência** (acessível a partir da lista de Ocorrências), com data, utilizador responsável e observação de cada mudança de estado — útil para auditoria e para os relatórios de tempo médio de reparação.

### 2.2 Estados de uma Ocorrência

`Aberta` → `Em diagnóstico` → `Aguardando orçamento` → `Aguardando aprovação` → `Em manutenção` → `Concluída` (ou `Cancelada`, a partir de qualquer estado).

- **Corretiva**: usada quando há um problema já reportado ou identificado na oficina.
- **Preventiva**: usada para manutenções agendadas — pode nascer manualmente ou ser gerada automaticamente pelo **Planeamento de Manutenção Preventiva** (secção 3.4).
- As ocorrências preventivas próximas (manuais ou por planeamento) aparecem no dashboard, ajudando a planear a agenda da oficina.

> **Nota:** o fluxo antigo (criar Orçamento directamente, sem passar por uma Ocorrência) continua disponível a partir do menu **Orçamentos**, para compatibilidade — mas o percurso recomendado é sempre começar por uma Ocorrência.

---

## 3. Módulos de Manutenção

Estes módulos são partilhados pelos vários perfis (com acções diferentes consoante o nível de acesso) e formam o núcleo do sistema CMMS.

### 3.1 Ocorrências

Menu **Manutenção › Ocorrências** (Administrador, Recepcionista, Técnico).

1. Clique em **Nova Ocorrência**.
2. Seleccione **um ou vários equipamentos** (Ctrl+clique para seleccionar mais do que um).
3. Escolha o **Tipo de Serviço**, o **Tipo de Manutenção** (Corretiva/Preventiva), a **Prioridade** (Baixa/Média/Alta/Urgente), a **Data Prevista** e, se souber já, o **Técnico Responsável**.
4. Descreva o problema/pedido e clique em **Abrir Ocorrência**.
5. Na lista, cada ocorrência tem ícones de acção: **Histórico** (linha do tempo de estados), **Diagnóstico**, **Criar Orçamento**, **Execução**, **Editar** e **Eliminar**.
6. Para atribuir ou mudar o técnico responsável, use o ícone de **Atribuir Técnico**. Para avançar o estado manualmente, use **Alterar Estado** (com observação opcional).

### 3.2 Diagnóstico Técnico

Acedido a partir do ícone de estetoscópio na lista de Ocorrências, ou pelo menu **Manutenção › Diagnósticos** (lista geral de todos os diagnósticos).

1. Na página da ocorrência, clique em **Novo Diagnóstico**.
2. Escolha o equipamento (se a ocorrência tiver mais do que um), descreva o **Problema Encontrado**, a **Solução Proposta** e as **Peças Solicitadas**.
3. Grave — a ocorrência avança automaticamente para **Em diagnóstico**.
4. Pode editar o diagnóstico enquanto não for encaminhado.
5. Quando estiver pronto para orçamentar, clique em **Encaminhar para Orçamento** — a ocorrência passa a **Aguardando orçamento** e o diagnóstico fica bloqueado para edição.

### 3.3 Orçamentos e Execução da Manutenção

- **Criar Orçamento a partir de uma Ocorrência**: clique no ícone de orçamento (💰) na lista de Ocorrências — o formulário de **Novo Orçamento** abre com o cliente e o equipamento já pré-preenchidos, e liga automaticamente o orçamento à ocorrência de origem.
- **Execução** (menu **Manutenção › Execuções**, ou pelo ícone na lista de Ocorrências): depois do orçamento aprovado, clique em **Iniciar Execução**. A ocorrência passa a **Em manutenção**.
  - Adicione as **peças efectivamente usadas** — o stock é reduzido automaticamente e fica registado no histórico de movimentações (secção 3.7).
  - Pode remover uma peça adicionada por engano — o stock é reposto.
  - Clique em **Encerrar** quando terminar — a ocorrência passa a **Concluída** e o(s) equipamento(s) ficam com estado **Concluído**.
- O fluxo antigo (criar orçamento directamente pelo menu **Orçamentos**, sem ocorrência associada) continua disponível para compatibilidade.

### 3.4 Planeamento de Manutenção Preventiva

Menu **Manutenção › Planeamento Preventivo** (Administrador, Técnico).

1. Clique em **Novo Plano**: escolha o equipamento, o tipo de serviço, o técnico responsável, a **periodicidade em dias** (ex.: 90 para manutenção trimestral) e a data de início.
2. Quando a **Próxima Execução** de um plano chega à data (ou já passou), a linha fica marcada como **Vencido** e aparece um botão **Gerar Ocorrências Pendentes**.
3. Ao clicar, o sistema cria automaticamente uma Ocorrência **Preventiva** para cada plano vencido, e avança a "próxima execução" desse plano pela periodicidade definida.
4. Pode **Activar/Inactivar** um plano (ícone de energia) sem o eliminar, ou editá-lo para ajustar a periodicidade e o técnico.
5. Planos a vencer nos próximos 30 dias aparecem também no **Dashboard**, no cartão "Manutenções Preventivas Próximas".

> Em produção, a geração de ocorrências preventivas pode ser automatizada (sem depender de alguém clicar no botão) através do script `cron_planeamento.php`, agendado nas Tarefas Agendadas do Windows ou no `cron` do Linux — ver comentário no topo desse ficheiro para instruções.

### 3.5 Categorias de Equipamento

Menu **Cadastrar › Produtos › Categorias de Equipamentos** (Administrador).

1. Clique em **Nova Categoria** (ex.: Computador, Impressora, Projetor, Switch, UPS).
2. Ao cadastrar ou editar um **Equipamento**, escolha a categoria — o sistema usa-a também para preencher automaticamente o campo "Tipo de Equipamento" (compatibilidade com relatórios antigos).
3. O formulário de Equipamento tem ainda uma secção **Dados Patrimoniais**: Código, Património, Nome do Equipamento, Departamento, Localização, Data de Aquisição e Observações.

### 3.6 Equipamentos Abatidos

Menu **Manutenção › Abatimentos** (Administrador aprova; Recepcionista e Técnico podem solicitar).

1. Clique em **Solicitar Abatimento**, escolha o equipamento e descreva o **Motivo**.
2. O pedido fica **Solicitado**, à espera de decisão do Administrador.
3. O Administrador vê a fila de pedidos pendentes e pode **Aprovar** (o equipamento passa a estado **Abatido** e deixa de aparecer nas listas de selecção de equipamento para novas ocorrências) ou **Rejeitar**.

### 3.7 Histórico de Movimentações de Stock

Ícone de histórico (🕓) na lista de **Produtos**, ou diretamente em `/movimentoEstoque?produto=<id>`.

Mostra todas as entradas e saídas de stock de um produto — compras, peças usadas em orçamentos/execuções, devoluções ao eliminar/reduzir uma peça, estornos de compras canceladas — com data, quantidade, origem e utilizador responsável. Substitui a antiga falta de rastreabilidade (antes só se via a quantidade actual em stock).

### 3.8 Configuração de Comissões

Menu **Comissões › Configurar %** (Administrador).

1. Por omissão existe uma configuração **global** (ex.: 30%), aplicada a todos os técnicos e serviços.
2. Clique em **Nova Configuração** para definir uma percentagem específica por **técnico**, por **tipo de serviço**, ou por ambos — a mais específica tem sempre prioridade sobre a mais genérica (técnico+serviço > técnico > serviço > global).
3. A percentagem realmente aplicada em cada comissão fica gravada nessa comissão (visível no ecrã **Comissões** do técnico), por isso alterar a configuração no futuro não altera comissões já calculadas.

---

## 4. Guia por Perfil de Acesso

### 4.1 Administrador (Gerente) — `adimin`

Tem acesso total ao sistema. Menu lateral: **Cadastrar › Pessoas / Produtos**, **Manutenção**, **Comissões**, **Contas**, **Tipo Serviço**, **Consultas**, **Relatório**, **Estatística**.

#### 📋 Exemplo prático: configurar o sistema pela primeira vez

Numa oficina nova, é o Administrador quem prepara o terreno antes de a equipa começar a trabalhar:

1. **Cadastrar Técnicos** (secção seguinte) — cada técnico criado recebe automaticamente uma conta de acesso por email.
2. **Cadastrar Recepcionistas**, se aplicável (menu **Cadastrar › Pessoas › Recepcionistas**).
3. **Criar Categorias de Equipamentos** (secção 3.5) — ex.: Computador, Impressora, Servidor — antes de a recepção começar a registar equipamentos.
4. **Criar Tipos de Serviço** (ex.: "Manutenção Preventiva", "Formatação", "Substituição de Peça") — vão aparecer depois no formulário de Ocorrência/Orçamento.
5. **Criar Categorias de Peças** e **cadastrar Produtos/Peças** com o stock inicial.
6. (Opcional) **Configurar Comissões** por técnico ou tipo de serviço (secção 3.8) — se não configurar nada, o sistema usa a percentagem global definida no `.env`.

A partir daqui, o dia-a-dia do Administrador é sobretudo **supervisão**: aprovar abatimentos (secção 3.6), acompanhar o dashboard, consultar relatórios e, ocasionalmente, intervir directamente numa Ocorrência ou Orçamento se for preciso.

#### Cadastrar Técnicos
1. Menu **Cadastrar › Pessoas › Técnicos**.
2. Preencha: Nome, Sobrenome, **Nº do Bilhete (BI)**, **NIF** (tem de ser igual ao BI), Email, Telefone, Morada e foto.
3. Clique em **Salvar**.
4. O sistema cria automaticamente uma conta de acesso (`nivel = tecnico`) com uma senha aleatória enviada por email para o técnico.
5. Para editar ou eliminar, use os botões de acção na tabela de técnicos (a eliminação remove também a conta de acesso).

> Se preferir criar um técnico de teste rapidamente sem passar pelo formulário, use o script opcional `database/seed_dados_padrao.sql` (cria `tecnico@gmail.com` / `tecnico123`).

#### Cadastrar Fornecedores
1. Menu **Cadastrar › Pessoas › Fornecedores**.
2. Preencha Nome, Sobrenome, Tipo de Pessoa (Singular/Colectiva), BI, NIF, Email, Morada e Telefone.
3. Clique em **Salvar**.

#### Produtos, Categorias e Equipamentos
1. Menu **Cadastrar › Produtos › Categorias** — crie categorias (ex.: "Peças de PC", "Periféricos") antes de cadastrar produtos.
2. Menu **Cadastrar › Produtos › Produtos** — preencha Nome, Referência, Categoria, Fornecedor, Estoque inicial, Valor de Compra, Valor de Venda, Descrição e foto.
3. Menu **Cadastrar › Produtos › Equipamentos** — regista o equipamento de um cliente: Nº de Série (obrigatório e único), IMEI (opcional), Tipo de Equipamento, Marca, Modelo, Estado, Cliente (por NIF) e Defeito Reportado.

#### Tipo Serviço
1. Menu **Tipo Serviço**.
2. Indique o **Nome** do serviço (ex.: "Manutenção Preventiva") e o **Valor** de referência.
3. Clique em **Salvar**. Estes tipos aparecem depois no formulário de Orçamento.
4. Editar/eliminar através dos botões na tabela.

#### Contas a Pagar e a Receber
- **Contas a Pagar**: normalmente geradas automaticamente a partir de **Compras**. Pode também criar uma conta manual (Descrição, Valor, Data de Vencimento). Use **Aprovar** para marcar como paga.
- **Contas a Receber**: geradas automaticamente quando um **Orçamento é aprovado**. Permite registar **Adiantamento** e **Aprovar/Pagar** o valor total.

#### Consultas (leitura)
Menu **Consultas**: Orçamentos, Serviço, Movimentação (caixa), Compras, Entrada de Equipamentos — listas filtráveis/pesquisáveis para acompanhamento.

**Estoque Baixo**: mostra produtos abaixo do nível mínimo definido em `STOCK_LEVEL` no `.env`.

#### Relatórios (PDF)
Menu **Relatório**: gera PDFs de Serviços, Orçamentos, Movimentação, Contas a Pagar/Receber, Compras, Vendas, lista de Equipamentos e Catálogo de Produtos. Clique no tipo de relatório desejado — abre um modal para escolher o período e depois gera o PDF.

#### Estatística (Gráficos)
Menu **Estatística**: gráficos de Movimentação de Entrada, Movimentação de Saída e Serviços Mais Prestados — úteis para acompanhar o desempenho do negócio.

---

### 4.2 Recepcionista — `recep`

Foco em atendimento ao cliente: cadastro de clientes/equipamentos e gestão financeira do dia-a-dia. É normalmente quem primeiro toca num caso novo.

#### 📋 Exemplo prático: um cliente chega com um computador avariado

1. **Menu Cadastro › Clientes** — verifique se o cliente já existe (pesquise pelo NIF). Se não existir, cadastre-o (Nome, NIF, Telefone, Email).
2. **Menu Cadastro › Equipamentos** — registe o computador: escolha o cliente, a categoria (ex.: "Computador"), marca/modelo, nº de série, e descreva o **Defeito Reportado** pelo cliente (ex.: "não liga").
3. **Menu Cadastro › Ocorrências** — clique em **Nova Ocorrência**, seleccione o equipamento acabado de registar, escolha a prioridade e, se souber, o técnico responsável. Clique em **Abrir Ocorrência**.
4. A partir daqui, o caso passa para o **Técnico** (secção 4.3), que vai diagnosticar e orçamentar. Você não precisa de fazer mais nada até o orçamento estar pronto.
5. **Alguns dias depois** — quando o técnico cria o orçamento, ele aparece em **Consultas › Orçamentos** (ou `/orcamentoRecepcao`). Contacte o cliente, explique o valor e, se aceitar, clique em **Aprovar** — isto gera automaticamente a **Conta a Receber**.
6. Registe o **Adiantamento**, se o cliente pagar uma parte já.
7. **Quando o técnico encerrar a execução** (equipamento pronto), volte à Conta a Receber, marque o **pagamento** e entregue o equipamento ao cliente.

#### Cadastrar Cliente
1. Menu **Cadastro › Clientes**.
2. Preencha Nome, Sobrenome, Nº do Bilhete, NIF, Email, Morada e Telefone.
3. Clique em **Salvar**.

#### Cadastrar Equipamento
1. Menu **Cadastro › Equipamentos**.
2. Escolha o cliente (por NIF), preencha Nº de Série, Tipo de Equipamento, Marca, Modelo, Estado e Defeito Reportado.
3. Clique em **Registar**.

#### Aprovar Orçamentos
1. Menu **Consultas › Orçamentos** (ou **Consultas › Consultar: Orçamentos**).
2. Localize o orçamento em aberto e clique em **Aprovar** — isto cria a Conta a Receber correspondente.
3. Pode também **Enviar Relatório** por email ao cliente a partir desta lista.

#### Contas a Pagar / Receber, Movimentação e Compras
Igual ao descrito para o Administrador (secção 4.1), mas sem acesso aos módulos de cadastro de Técnicos/Fornecedores/Tipo de Serviço.

#### Relatórios
Mesmo menu **Relatório** descrito acima, com os mesmos tipos de PDF disponíveis.

#### Ocorrências e Abatimentos
Pode abrir Ocorrências (menu **Cadastro › Ocorrências**, junto de Clientes/Equipamentos) e solicitar Abatimento de equipamentos — ver secções 3.1 e 3.6.

---

### 4.3 Técnico — `tecnico`

Foco na execução de orçamentos e serviços atribuídos. É quem mais usa o menu **Manutenção** no dia-a-dia.

#### 📋 Exemplo prático: resolver uma ocorrência atribuída a mim

1. **Menu Manutenção › Ocorrências** — encontre a ocorrência (a recepção já registou o cliente e o equipamento, e abriu a ocorrência). Se ainda não tiver técnico atribuído, use o ícone **Atribuir Técnico** para se atribuir a si próprio.
2. Clique no ícone de **Diagnóstico** (estetoscópio) — descreva o problema encontrado e a solução proposta (ex.: "Fonte de alimentação queimada — substituir por uma nova"). Grave.
3. Quando tiver a certeza do diagnóstico, clique em **Encaminhar para Orçamento**.
4. Volte à lista de Ocorrências e clique no ícone de **Criar Orçamento** (💰) — o formulário já vem com o cliente e o equipamento preenchidos. Complete o **Tipo de Serviço**, **Valor (Mão de Obra)**, **Garantia** e **Data de Entrega** prevista, e grave.
5. Aguarde a recepção aprovar o orçamento junto do cliente (secção 4.2). Não precisa de fazer nada enquanto isso.
6. **Depois de aprovado** — volte à Ocorrência e clique em **Iniciar Execução** (menu **Manutenção › Execuções**, ou pelo ícone na lista de Ocorrências).
7. Vá adicionando as **peças que realmente usar** (ex.: a fonte de alimentação nova) — o stock é reduzido automaticamente.
8. Quando terminar a reparação, clique em **Encerrar** — a ocorrência fica **Concluída**, o equipamento fica marcado como "Concluído", e a sua **comissão** é calculada e registada automaticamente.
9. Pode confirmar o valor da comissão em **Comissões › Comissões**.

#### Criar Orçamento
1. Menu **Orçamentos e Serviços › Orçamentos**.
2. Preencha:
   - **NIF do Cliente** (com pesquisa automática)
   - **Nº de Série do Equipamento**
   - **Serviço** (Tipo de Serviço já cadastrado, ex.: "Manutenção Corretiva")
   - **Descrição** do trabalho a realizar
   - **Data da Entrega** prevista
   - **Garantia** (dias)
   - **Valor (Mão de Obra)**
   - **Observação** do equipamento
3. Clique em **Salvar**. O orçamento fica com estado **Aberto** até ser aprovado pela recepção/gerência.
4. Pode editar ou eliminar orçamentos ainda não aprovados através dos botões da tabela.

#### Registar Serviço
1. Menu **Orçamentos e Serviços › Serviços**.
2. Associe o serviço executado ao orçamento/equipamento (NIF do cliente, Nº de série, Tipo de Serviço, Descrição, Data de Entrega, Garantia, Valor da Mão de Obra e Observações).
3. Clique em **Salvar** e, quando concluído, use **Sim Termina** para fechar o serviço.

#### Comissões
1. Menu **Comissões › Comissões** — mostra o valor acumulado de comissão do técnico, incluindo a **percentagem aplicada** em cada linha (secção 3.8).
2. **Relatório Comissão** — abre um modal para gerar o relatório em PDF por período.

#### Manutenção (Ocorrências, Diagnóstico, Execução, Planeamento)
O menu **Manutenção** dá acesso directo às Ocorrências atribuídas, aos Diagnósticos, às Execuções e ao Planeamento Preventivo — ver secções 3.1 a 3.4. É o percurso de trabalho principal do técnico, complementar (não substituto) ao menu **Orçamentos e Serviços**.

---

## 5. Módulos de Apoio (Estoque / Compras / Vendas)

- **Estoque Baixo**: lista de produtos que atingiram o nível mínimo (`STOCK_LEVEL`) — reponha o stock criando uma **Compra**.
- **Compras**: ao adicionar estoque a um produto (Fornecedor + Valor de Compra + Quantidade), o sistema gera automaticamente o registo em **Compras** e a respectiva **Conta a Pagar**.
- **Vendas**: histórico de vendas de peças/produtos avulsas, consultável na lista de Vendas.
- **Histórico de Movimentações**: ver secção 3.7.

---

## 6. Perguntas Frequentes

| Situação | O que fazer |
|----------|-------------|
| Não recebo o email com a senha do técnico/código de recuperação | Verifique as credenciais `SMTP_*` no `.env` (por defeito usa Mailtrap, apenas para testes) |
| NIF e BI diferentes ao cadastrar Técnico/Fornecedor/Cliente | O sistema exige que **NIF seja igual ao BI** no cadastro de Técnicos (validação de segurança) |
| Email ou NIF "já existente" ao cadastrar | Cada email/NIF/BI só pode estar associado a um registo — verifique se a pessoa já está cadastrada |
| Orçamento não aparece para aprovação | Confirme que foi guardado com sucesso e que está com estado "Aberto" na lista de Orçamentos |
| Não consigo abrir uma Ocorrência | Confirme que seleccionou pelo menos um equipamento — é o único campo obrigatório além da descrição |
| Diagnóstico não deixa editar | Diagnósticos já **encaminhados para orçamento** ficam bloqueados para edição — é intencional, para preservar o histórico |
| Peça não aparece disponível para adicionar à Execução | Confirme que tem stock (`estoque >= 1`) — o sistema não permite reduzir stock abaixo de zero |
| Equipamento não aparece na lista para nova Ocorrência/Abatimento | Equipamentos com estado **Abatido** ficam excluídos dessas listas |
| Erros técnicos, instalação ou configuração | Consulte a secção **Resolução de Problemas** no [README.md](README.md#resolução-de-problemas) |

---

## 7. Referência Rápida de URLs

| Módulo | URL relativa |
|--------|--------------|
| Login | `/` |
| Dashboard | `/home` |
| Técnicos | `/tecnico` |
| Fornecedores | `/fornecedor` |
| Categorias de Peças | `/categoria` |
| Categorias de Equipamentos | `/categoriaEquipamento` |
| Produtos | `/produto` |
| Histórico de Movimentações | `/movimentoEstoque?produto=<id>` |
| Equipamentos | `/equipamento` |
| Equipamentos Abatidos | `/abatimento` |
| Clientes | `/cliente` |
| Tipo de Serviço | `/tipoServico` |
| Ocorrências | `/ocorrencia` |
| Diagnósticos | `/diagnostico` |
| Execuções | `/execucao` |
| Planeamento Preventivo | `/planeamento` |
| Configuração de Comissões | `/comissaoConfig` |
| Orçamentos (técnico) | `/orcamento` |
| Orçamentos (recepção) | `/orcamentoRecepcao` |
| Serviços | `/servico` |
| Contas a Pagar | `/contasPagar` |
| Contas a Receber | `/contaReceber` |
| Movimentação | `/movimentacao` |
| Compras | `/compras` |
| Vendas | `/vendas` |
| Comissões | `/comissoes` |
| Entrada de Equipamentos | `/entradaEquipamento` |
| Relatórios | `/relatorio` |
| Gráficos/Estatística | `/graficos` |
| Perfil | `/perfil` |
| Sair | `/sair` |

---

*Ver também: [README.md](README.md) (instalação), [GUIA_EXECUCAO.md](GUIA_EXECUCAO.md) (execução rápida), [CHANGELOG.md](CHANGELOG.md) (histórico de alterações).*
