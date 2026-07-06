# Manual de Utilização — Sistema de Gestão de Assistência Técnica Informática

Guia passo-a-passo de como usar o sistema depois de instalado. Para instalar o sistema pela primeira vez, siga o **[README.md](README.md#instalação-passo-a-passo)** ou o **[GUIA_EXECUCAO.md](GUIA_EXECUCAO.md)**.

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

## 2. Fluxo Típico de uma Reparação

Este é o percurso normal de um equipamento, do momento em que entra na oficina até ser entregue ao cliente:

1. **Recepcionista** cadastra o **Cliente** (se ainda não existir).
2. **Recepcionista** cadastra o **Equipamento** associado a esse cliente (nº de série, marca, modelo, defeito reportado).
3. **Técnico** cria um **Orçamento** para esse equipamento, escolhendo o **Tipo de Serviço**, valor da mão-de-obra, garantia e data prevista de entrega.
4. **Recepcionista** (ou Gerente) **aprova o Orçamento** junto do cliente — isto gera automaticamente uma **Conta a Receber**.
5. **Técnico** regista o **Serviço** executado (diagnóstico, peças usadas) e termina o orçamento.
6. Sistema calcula a **Comissão** do técnico automaticamente (se activado no `.env`).
7. **Recepcionista/Gerente** regista o **pagamento** na Conta a Receber e marca o equipamento como entregue.

---

## 3. Guia por Perfil de Acesso

### 3.1 Administrador (Gerente) — `adimin`

Tem acesso total ao sistema. Menu lateral: **Cadastrar › Pessoas / Produtos**, **Contas**, **Tipo Serviço**, **Consultas**, **Relatório**, **Estatística**.

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

### 3.2 Recepcionista — `recep`

Foco em atendimento ao cliente: cadastro de clientes/equipamentos e gestão financeira do dia-a-dia.

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
Igual ao descrito para o Administrador (secção 3.1), mas sem acesso aos módulos de cadastro de Técnicos/Fornecedores/Tipo de Serviço.

#### Relatórios
Mesmo menu **Relatório** descrito acima, com os mesmos tipos de PDF disponíveis.

---

### 3.3 Técnico — `tecnico`

Foco na execução de orçamentos e serviços atribuídos.

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
1. Menu **Consultas › Comissões** — mostra o valor acumulado de comissão do técnico (percentagem definida em `COMMISSION_VALUE` no `.env`, se `TECHNICIAN_COMMISSION=SIM`).
2. **Relatório Comissão** — abre um modal para gerar o relatório em PDF por período.

---

## 4. Módulos de Apoio (Estoque / Compras / Vendas)

- **Estoque Baixo**: lista de produtos que atingiram o nível mínimo (`STOCK_LEVEL`) — reponha o stock criando uma **Compra**.
- **Compras**: ao adicionar estoque a um produto (Fornecedor + Valor de Compra + Quantidade), o sistema gera automaticamente o registo em **Compras** e a respectiva **Conta a Pagar**.
- **Vendas**: histórico de vendas de peças/produtos avulsas, consultável na lista de Vendas.

---

## 5. Perguntas Frequentes

| Situação | O que fazer |
|----------|-------------|
| Não recebo o email com a senha do técnico/código de recuperação | Verifique as credenciais `SMTP_*` no `.env` (por defeito usa Mailtrap, apenas para testes) |
| NIF e BI diferentes ao cadastrar Técnico/Fornecedor/Cliente | O sistema exige que **NIF seja igual ao BI** no cadastro de Técnicos (validação de segurança) |
| Email ou NIF "já existente" ao cadastrar | Cada email/NIF/BI só pode estar associado a um registo — verifique se a pessoa já está cadastrada |
| Orçamento não aparece para aprovação | Confirme que foi guardado com sucesso e que está com estado "Aberto" na lista de Orçamentos |
| Erros técnicos, instalação ou configuração | Consulte a secção **Resolução de Problemas** no [README.md](README.md#resolução-de-problemas) |

---

## 6. Referência Rápida de URLs

| Módulo | URL relativa |
|--------|--------------|
| Login | `/` |
| Dashboard | `/home` |
| Técnicos | `/tecnico` |
| Fornecedores | `/fornecedor` |
| Categorias | `/categoria` |
| Produtos | `/produto` |
| Equipamentos | `/equipamento` |
| Clientes | `/cliente` |
| Tipo de Serviço | `/tipoServico` |
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
