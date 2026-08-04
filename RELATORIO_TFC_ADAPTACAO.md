# Relatório de Auditoria e Adaptação Institucional (TFC)

**Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos — Universidade Lusíada de Angola**

**Data:** 04 de agosto de 2026 · **Versão resultante:** 5.0.0 · **Arquitetura:** Cliente-Servidor (física) / MVC (lógica) — mantida sem alterações

---

## 1. Contexto e objetivo

O sistema nasceu como uma oficina mecânica, foi mais tarde renomeado para "assistência técnica informática" e, na versão 4.0.0, recebeu uma camada de gestão de manutenção (CMMS) sobreposta a um núcleo ainda comercial: clientes externos, orçamentos, vendas, comissões de técnicos e contas a pagar/receber. O objetivo deste trabalho foi transformar o sistema numa solução **exclusivamente institucional** para o Departamento de TI da Universidade Lusíada de Angola — sem qualquer funcionalidade comercial — e, no mesmo processo, auditar e corrigir todo o sistema (base de dados, backend, frontend, permissões, sessões, autenticação, DataTables, SQL, rotas, uploads, mensagens, e-mails e relatórios).

Este documento cobre, por secção: os problemas encontrados, as correções realizadas, as funcionalidades alteradas/criadas/removidas, a justificação de cada decisão face ao contexto da Universidade, e recomendações para trabalho futuro.

---

## 2. Problemas encontrados na auditoria inicial

### 2.1 Segurança e autenticação
- Palavras-passe guardadas e verificadas em **MD5**, um algoritmo de hash criptograficamente quebrado.
- `AdmsLogin::login()` continha um `var_dump($this->resultadoBd)` incondicional após autenticação bem-sucedida, expondo no ecrã todos os dados do utilizador — incluindo o hash da senha.
- `core/ConfigController.php` executava uma `SELECT`/`INSERT` para (re)criar o utilizador administrador em **cada pedido HTTP**, com uma senha fixa (`MD5('12345')`) escrita diretamente no código.
- `core/Permissao.php` só verificava se havia sessão iniciada — **qualquer** utilizador autenticado (independentemente do papel) conseguia aceder a qualquer rota do sistema digitando o URL diretamente. Não existia qualquer controlo de acesso por papel.
- `Sair.php` terminava a sessão com `unset()` seletivo de variáveis específicas, em vez de destruir a sessão por completo — outras variáveis de sessão ficavam órfãs.
- Várias consultas SQL (relatórios/dashboard, validações de duplicados) usavam concatenação direta de variáveis dentro de strings SQL, apesar de o resto do código usar `PDO::prepare()` — uma inconsistência que deixava uma superfície de risco de injeção de SQL.
- Uploads de imagem validados apenas pela extensão do nome do ficheiro (sem verificar o tipo real do conteúdo nem limite de tamanho).

### 2.2 Modelo de dados e acoplamento comercial
- `equipamento.idcliente` ligava cada equipamento a um cliente externo — não fazia sentido para um ativo institucional.
- `ocorrencias.id_orcamento` e `execucao_manutencao.id_orcamento` ligavam o núcleo de manutenção (CMMS) diretamente ao núcleo comercial (`orcamentos`), tornando os dois inseparáveis.
- `ocorrencias` tinha simultaneamente as colunas `estado` e `status` a guardar a mesma informação (duplicação, risco de dessincronização).
- `produto.valor_venda` (preço de venda) não fazia sentido para peças de uso interno.
- `usuario.nivel` usava o valor `'adimin'` (erro ortográfico persistente em todo o código, desde a origem do projeto) e ainda tinha resíduos do valor legado `'mecanico'`.

### 2.3 Terminologia e mensagens
- Interface inteira em vocabulário de oficina comercial: "Cliente", "Orçamento", "Comissão do Mecânico", "Venda", "Conta a Receber/Pagar".
- Mensagens de erro/sucesso com gramática incorreta ("Cadastrado Sem Sucesso!", "Precissa", "Garatia", "Adiministrador"), tom informal inconsistente ("Tens a certeza...") e pelo menos um erro de cópia/substituição (`"Clica Sim Para Pagar Esse Técnico"` para uma ação de **eliminar**, não de pagar).
- Mojibake de UTF-8 (`Ã§Ã£o` em vez de `ção`) concentrado nos ecrãs de Contas a Pagar, Movimentação e vários relatórios comerciais.
- Título e meta-tags HTML de todas as páginas anunciavam "Oficina, Mecânica, Carros, Motas, Reparação".

### 2.4 E-mails
- O e-mail de boas-vindas ao técnico (`AdmsTecnico::enviarEmail()`) e o e-mail de relatório de orçamento tinham o servidor SMTP, utilizador e senha **fixos no código** (uma conta de teste Mailtrap), ignorando por completo a configuração do `.env`.
- O remetente estava fixo ao Gmail pessoal do antigo autor do projeto (`josimardasilvaf36@gmail.com`, nome "Josimar"), independentemente do que estivesse configurado como e-mail institucional.
- Nenhum destes dois pontos definia `CharSet = 'UTF-8'`, arriscando corrupção de acentos no corpo do e-mail.
- O e-mail de orçamento tinha uma linha `<td>Mecânico:</td><td>Josimar Ferreira</td>` **fixa**, independentemente de quem fosse o técnico real.

### 2.5 DataTables e frontend
- Inicialização de DataTables totalmente genérica (`$('#dataTable').DataTable();`), sem idioma português nem responsividade.
- `pgEstoque.php` tinha a ordem das colunas do `<thead>` (Nome, Referência, Categoria, Fornecedor…) diferente da ordem real dos `<td>` gerados no `<tbody>` (Nome, Categoria, Fornecedor, Referência…) — dados a aparecer sob a coluna errada.
- `pgAbatimento.php` usava um `colspan` fixo de 6 na linha de "sem registos", mesmo quando o número real de colunas visíveis era 5 (para o papel sem permissão de aprovação).
- Formulário de login com `<input type="btnUsuario">` (tipo HTML inválido) e placeholders com erros ("Digete").
- Página de "Editar Perfil" a submeter por **GET** (método errado para uma operação que altera dados).

### 2.6 Lógica aplicacional
- `AdmsTecnico::editTecnico()` gerava e gravava uma **nova senha aleatória** sempre que se editava qualquer dado básico do técnico (nome, telefone, etc.), sem avisar nem enviar essa nova senha — bloqueando efetivamente o acesso do técnico.
- Vista de criação de Técnico acedia a `$valorForm['foto']` sem essa variável estar necessariamente definida — aviso de "undefined variable" em todos os acessos diretos à página.
- `ConfigController::__construct()` (versão implícita) e outros métodos com parâmetros `array $x = null` sem o `?` explícito — descontinuado desde o PHP 8.1 (aviso de depreciação).
- `core/ConfigController.php` executava DDL (`CREATE TABLE IF NOT EXISTS ocorrencias`) implicitamente a cada pedido — já corrigido antes deste trabalho, mas confirmado e mantido corrigido.

---

## 3. Correções realizadas

### 3.1 Base de dados — reconstrução institucional (`database/schema.sql`)
Ficheiro reescrito de raiz como fonte de verdade única (v5.0.0), com todas as tabelas comerciais removidas e o modelo de dados religado exclusivamente ao domínio institucional. Ver secção 4 para a lista completa de tabelas removidas/criadas/alteradas.

### 3.2 Autenticação, sessões e permissões
- Senhas migradas para `password_hash()` / `password_verify()` (`AdmsLogin.php`, `AdmsRecuperarSenha::alterarSenha()`, criação de técnico, alteração de conta).
- `var_dump()` removido do fluxo de login.
- Auto-seed do admin removido de `ConfigController::config()` — o utilizador Gerente inicial só é criado pelo `schema.sql`.
- `core/Permissao.php` reescrito com uma segunda whitelist de rotas exclusivas do Gerente; uma tentativa de acesso por um Técnico é redirecionada para o dashboard com mensagem "Acesso negado".
- `Sair.php` reescrito para `session_unset()` + `session_destroy()` + limpeza do cookie de sessão; `AdmsLogin::login()` chama `session_regenerate_id(true)` num login bem-sucedido.
- Todas as consultas de validação/relatório reescritas com `bindParam`/marcadores de posição.
- Upload de imagens (peças, técnicos) reforçado: validação da extensão **e** do tipo MIME real via `finfo`, limite de 5 MB, verificação `is_uploaded_file()`.

### 3.3 Terminologia, menus e mensagens
- Título, meta-tags, e-mails e todas as mensagens do sistema reescritos em português correto, sem vocabulário comercial.
- Menu lateral (`app/adms/Views/include/dashboard.php`) totalmente reescrito para dois papéis — Gerente e Técnico — seguindo exatamente a estrutura pedida (Cadastros, Manutenção, Stock, Relatórios, Configurações / Ocorrências, Diagnósticos, Execuções, Planeamento, Equipamentos, Histórico, Perfil).
- Corrigido o erro de cópia "Pagar" → "eliminar" em `pgTecnico.php` e `pgCategoria.php`.
- Mojibake eliminado (concentrado nos ecrãs comerciais, removidos).

### 3.4 E-mails
- `AdmsRecuperarSenha` já usava `Core\Config` corretamente — apenas ajustado o nome/remetente institucional e o *fallback*.
- `AdmsTecnico` reescrito com um único método `enviarCredenciais()`, config-driven (`Core\Config::get('SMTP_*'...)`), `CharSet = 'UTF-8'`, remetente institucional, sem credenciais fixas nem nome de pessoa "hardcoded".

### 3.5 DataTables e frontend
- `app/adms/assets/js/datatables-demo.js` reescrito com idioma português completo e `responsive: true`; adicionada a extensão *DataTables Responsive* (JS + CSS) a `head.php`/`footer.php`.
- `pgEstoque.php` reescrito de raiz — colunas do `thead`/`tbody` alinhadas.
- `colspan` de `pgAbatimento.php` tornado dinâmico consoante o papel.
- Formulário de login corrigido (`type="email"`, placeholders corretos).
- "Editar Perfil" migrado de GET para POST (`Perfil.php`, `head.php`).

### 3.6 Lógica aplicacional
- `AdmsTecnico::editTecnico()` deixa de tocar na senha ao editar dados básicos.
- `pgTecnico.php` corrigido para não aceder a `$valorForm['foto']` sem verificação.
- Parâmetros `nullable` implícitos tornados explícitos em todo o código tocado (`AdmsLogin`, `AdmsEquipamento`, `AdmsMovimentoEstoque`, `core/ConfigView.php`).
- `Conn.php` passa a declarar `charset=utf8mb4` explicitamente na DSN do PDO (antes dependia do valor por omissão do servidor).

---

## 4. Funcionalidades alteradas, criadas e removidas

### 4.1 Removido por completo (módulos, tabelas, rotas)
`Cliente`, `Vendas`, `Comissoes`, `ComissaoConfig`, `ContasPagar`, `ContaReceber`, `Movimentacao`, `Orcamento`, `OrcamentoRecepcao`, `AddProdutoOrcamento`, `Servico`, `TipoServico`, `Recepcionista`, `EntradaEquipamento`, `Dashboard` (relatórios comerciais), `Consultas`, `Chat` (template estático da AdminLTE, nunca funcional), `RelatorioTecnico`; tabelas `clientes`, `orcamentos`, `orc_prod`, `vendas`, `comissao`, `comissao_config`, `contas_apagar`, `conntas_areceber`, `movimentacao`, `tipo_servico`, `entrada_equipamento`, `entrada_veiculo`, `veiculo`, `recepcionista`; o papel `recep`.

### 4.2 Criado de novo
- Módulos: **Utilizadores**, **Departamentos**, **Tipos de Manutenção**, **Histórico** (consolidado).
- Tabelas: `departamentos`, `tipo_manutencao`.
- Dashboards dedicados por papel (Gerente/Técnico) com os indicadores institucionais pedidos.
- Estatísticas de manutenção (ocorrências por mês, preventiva vs. corretiva, equipamentos por estado, peças mais usadas), substituindo os gráficos de movimentação de caixa.
- Relatórios institucionais (Equipamentos, Técnicos, Ocorrências, Diagnósticos, Manutenções, Planeamentos, Histórico, Fornecedores, Stock, Compras) com impressão e exportação CSV.
- Ecrã de **Configurações** (leitura dos dados institucionais definidos em `.env`).

### 4.3 Reconstruído
- **Equipamentos**: passam a pertencer a um **Departamento** e a ter um **Responsável** (antes: cliente), com todos os campos pedidos (código patrimonial, tipo, marca, modelo, nº de série, estado, localização, data de aquisição, garantia, fornecedor) e histórico de manutenção consolidado.
- **Fornecedores**: mantidos exclusivamente como fonte de equipamentos/peças/consumíveis, sem qualquer resquício comercial.
- **Peças e Consumíveis** (`produto`): perde o preço de venda; ganha stock mínimo por peça.
- **Compras**: reconstruída como simples registo de aquisição junto de um fornecedor, sem qualquer ligação a contas a pagar.
- **Stock**: Entradas/Saídas passam a ser vistas filtradas sobre o mesmo ledger `movimento_estoque` já existente.
- **Ocorrências/Diagnóstico/Execução/Planeamento Preventivo/Abatimento**: mantidos (já eram o núcleo CMMS do sistema), mas desacoplados de `orcamentos` e com os estados simplificados ao fluxo puro `Ocorrência → Diagnóstico → Execução → Conclusão → Histórico`.

---

## 5. Justificação das alterações face ao contexto da Universidade Lusíada de Angola

| Alteração | Justificação |
|---|---|
| Remoção de Clientes/Vendas/Orçamentos/Comissões/Contas a Pagar-Receber | A Universidade não vende serviços nem produtos a terceiros — os equipamentos são ativos internos, geridos por um departamento de TI, não por uma oficina com fins lucrativos. Manter estes módulos manteria uma falsa premissa comercial no sistema. |
| Equipamento pertence a um Departamento, não a um Cliente | Reflete a realidade organizacional de uma universidade: os computadores/impressoras/servidores pertencem a departamentos (Secretaria, Biblioteca, Reitoria, etc.), não a clientes externos. |
| Papéis reduzidos a Gerente/Técnico | Não existe atendimento ao público nem front-office comercial — só existe quem gere o parque informático (Gerente de TI) e quem executa a manutenção (Técnicos). |
| Fluxo único Ocorrência→Diagnóstico→Execução→Conclusão→Histórico | É o ciclo de vida real de um pedido de manutenção interna, sem a etapa de negociação/aprovação comercial de um orçamento. |
| Peças e Consumíveis sem preço de venda | O stock existe para dar suporte à manutenção interna, não para revenda — faz sentido registar o custo de aquisição (para efeitos de gestão orçamental do departamento), mas não um preço de venda. |
| Dashboards sem indicadores financeiros | Pedido explícito: os indicadores relevantes para a gestão de TI são operacionais (equipamentos, ocorrências, manutenções), não financeiros. |
| Fornecedores sem funcionalidade comercial | Os fornecedores continuam a existir — a Universidade compra equipamento e material — mas o sistema não vende nada a ninguém, pelo que não há necessidade de faturação, preços de venda ou clientes desses fornecedores. |
| Segurança reforçada (hash de senhas, controlo de acesso por papel) | Um sistema institucional universitário lida com dados de equipamentos e pessoal — justifica-se o mesmo nível de exigência de segurança de qualquer sistema de informação institucional, independentemente de não movimentar dinheiro. |

---

## 6. Testes realizados

Testado em ambiente local (XAMPP, PHP 8.2.12, MariaDB 10.4, Apache 2.4.58), com o servidor a correr e testes reais via HTTP (login, submissão de formulários, verificação de respostas e do log de erros do Apache):

- `php -l` sobre a totalidade dos ficheiros PHP alterados — **zero erros de sintaxe**.
- Recriação completa da base de dados a partir de `database/schema.sql` — sem erros.
- `health.php` — `status: ok` em todas as verificações.
- **Fluxo completo de manutenção**, ponta a ponta, com dados reais: Departamento → Equipamento → Ocorrência (Aberta) → Diagnóstico (→ Em diagnóstico) → Encaminhar para Execução (→ Aguardando execução) → Iniciar Execução (→ Em execução; equipamento → Em Manutenção) → Encerrar (→ Concluída; equipamento → Disponível) → confirmado no Histórico.
- **Módulo de Stock**: criação de Categoria, Peça e Compra — stock incrementado corretamente.
- **Controlo de acesso por papel**: login como Técnico e tentativa de acesso direto por URL a 12 rotas exclusivas do Gerente (`produto`, `fornecedor`, `departamento`, `tipoManutencao`, `compras`, `estoque`, `relatorio`, `graficos`, `configuracoes`, `categoriaEquipamento`, `abatimento`, `movimentoEstoque`) — **todas corretamente bloqueadas** (redirecionamento para o dashboard).
- Todas as rotas do Gerente (20+) testadas com sessão autenticada — **HTTP 200 e zero ocorrências de "Fatal error", "Warning", "Notice" ou "Deprecated" no corpo da resposta**.
- Todos os 10 relatórios institucionais testados (visualização + exportação CSV com BOM UTF-8 e cabeçalhos corretos).
- Logout testado — sessão destruída, acesso subsequente bloqueado corretamente.
- Rota inexistente testada — redireciona para login com mensagem apropriada.
- Log de erros do Apache monitorizado antes/depois de cada ronda de testes — **zero avisos/erros PHP novos** gerados pelo sistema já corrigido (o log conservava apenas entradas históricas, anteriores a esta intervenção, usadas como evidência de auditoria na secção 2).
- Base de dados reposta no estado limpo (apenas o *seed* institucional) após os testes.

> **Nota sobre impressão e e-mail:** a impressão foi validada através do botão "Imprimir" (usa a função nativa do browser, incluindo "Guardar como PDF"). O envio de e-mails foi validado ao nível do código (configuração via `.env`, sem credenciais fixas) — o envio efetivo depende de credenciais SMTP reais, não incluídas neste ambiente de testes.

---

## 7. Credenciais de acesso (ambiente de desenvolvimento)

| Papel | E-mail | Senha |
|-------|--------|-------|
| Gerente de TI | `gerente.ti@ula.co.ao` | `Lusiada@2026` |

**Altere esta senha imediatamente em produção.**

---

## 8. Recomendações para melhorias futuras

1. **Segurança**: adicionar autenticação de dois fatores (2FA) para o papel Gerente; considerar `rate limiting` no login; rever periodicamente os registos de `control_usuario`.
2. **Testes automatizados**: o projeto não tem nenhuma suite de testes (PHPUnit); a introdução de testes unitários aos `Models` e testes de integração às rotas reduziria o risco de regressão em iterações futuras.
3. **Geração real de PDF**: os relatórios são atualmente HTML "print-friendly" (o utilizador usa "Guardar como PDF" do browser). A dependência `mpdf/mpdf` já está instalada mas não é usada — poderia gerar-se PDF real no servidor para relatórios formais/arquivo.
4. **Reset de senha no primeiro acesso**: forçar a alteração da senha do Gerente ao primeiro login, em vez de depender de instrução manual.
5. **Notificações**: o Planeamento Preventivo já gera ocorrências automaticamente (via `cron_planeamento.php`); poder-se-ia acrescentar notificação por e-mail ao técnico responsável quando lhe é atribuída uma ocorrência.
6. **Auditoria de dados**: `ocorrencia_historico` já regista mudanças de estado; poderia estender-se um registo semelhante a Equipamentos (alterações de responsável/departamento) para auditoria patrimonial completa.
7. **Documentação da API**: `docs.php`/`swagger.json` foram atualizados ao nível do resumo (títulos, tags, lista de rotas); um detalhamento completo de parâmetros e esquemas de resposta por rota ficaria mais robusto com anotações geradas diretamente a partir dos Controllers.
8. **Gestão de contas do Gerente**: atualmente só é possível gerir o *papel*/estado de contas existentes em `/utilizador`; a criação direta de uma nova conta de Gerente (sem passar pelo fluxo de Técnico) teria de ser feita manualmente na base de dados — vale a pena um pequeno formulário dedicado.

---

## 9. Ficheiros de referência

- `README.md` — instalação e arquitetura.
- `GUIA_EXECUCAO.md` — arranque rápido.
- `MANUAL_UTILIZACAO.md` — manual de utilização por papel.
- `ANALISE_PROJETO.md` — estado técnico e segurança.
- `CHANGELOG.md` — histórico de versões (entrada v5.0.0 com o detalhe desta adaptação).
