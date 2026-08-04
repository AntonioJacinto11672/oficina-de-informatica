# Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos — Universidade Lusíada de Angola

Sistema web institucional para gestão da manutenção de equipamentos informáticos do departamento de TI da Universidade Lusíada de Angola, desenvolvido em PHP com arquitectura MVC (Model-View-Controller) sobre uma arquitectura física Cliente-Servidor. Não é uma oficina comercial: não há clientes externos, vendas, orçamentos comerciais ou comissões — apenas a gestão interna do ciclo de vida de manutenção dos equipamentos da instituição.

---

## Funcionalidades

| Módulo | Descrição |
|--------|-----------|
| **Autenticação** | Login com sessão segura (`password_hash`/`password_verify`) e dois papéis: Gerente e Técnico |
| **Recuperação de Senha** | Envio de código de 6 dígitos por e-mail com validade de 30 min |
| **Utilizadores** | Ativação/desativação de contas e alteração de papel |
| **Técnicos** | Cadastro, edição e gestão de técnicos de informática |
| **Departamentos** | Departamentos da Universidade, a quem os equipamentos pertencem |
| **Equipamentos** | Registo completo (código patrimonial, tipo, marca, modelo, nº de série, estado, localização, departamento, responsável, fornecedor, garantia) e histórico de manutenção |
| **Fornecedores** | Fornecedores de equipamentos, peças e consumíveis (sem funcionalidade comercial) |
| **Tipos de Manutenção** | Catálogo técnico de tipos de manutenção (Preventiva/Corretiva) |
| **Peças e Consumíveis** | Stock interno com alerta de stock mínimo |
| **Ocorrências** | Ponto de entrada do fluxo de manutenção — abertura, atribuição de técnico, prioridade |
| **Diagnósticos** | Registo do diagnóstico técnico ligado a uma ocorrência |
| **Execuções** | Execução da manutenção, com consumo de peças e atualização automática do stock |
| **Planeamento Preventivo** | Planos periódicos por equipamento, com geração automática de ocorrências vencidas |
| **Abatimento de Equipamentos** | Workflow de fim de vida útil com aprovação do Gerente |
| **Histórico** | Consulta de todas as manutenções concluídas/canceladas |
| **Stock** | Entradas, Saídas, Compras a fornecedores e alerta de Stock Baixo |
| **Relatórios** | Equipamentos, Técnicos, Ocorrências, Diagnósticos, Manutenções, Planeamentos, Histórico, Fornecedores, Stock e Compras — com impressão e exportação CSV |
| **Estatísticas** | Gráficos de ocorrências por mês, preventiva vs. corretiva, equipamentos por estado e peças mais utilizadas |

---

## Fluxo de Manutenção

```
Ocorrência → Diagnóstico → Execução da Manutenção → Conclusão → Histórico
```

1. Uma **Ocorrência** é aberta para um ou mais equipamentos (categoria Preventiva ou Corretiva, prioridade, técnico responsável).
2. O técnico regista o **Diagnóstico** (problema, solução proposta, peças necessárias) — a ocorrência avança para "Em diagnóstico".
3. O diagnóstico é encaminhado para **Execução** — a ocorrência avança para "Aguardando execução".
4. O técnico inicia a execução — o equipamento passa a estado "Em Manutenção" — regista as peças usadas (o stock é atualizado automaticamente) e encerra a execução.
5. Ao encerrar, a ocorrência é marcada **Concluída**, o equipamento volta a "Disponível" e o registo passa a constar do **Histórico**.

Para manutenção preventiva, um **Plano de Manutenção Preventiva** por equipamento gera automaticamente novas ocorrências quando a periodicidade vence (botão manual ou `cron_planeamento.php` agendado).

---

## Stack Tecnológica

| Componente | Versão | Função |
|-----------|--------|--------|
| PHP | 8.2+ | Backend / Lógica de negócio |
| MySQL | 8.0+ / MariaDB 10.4+ | Base de dados relacional |
| Apache | 2.4+ | Servidor web (com mod_rewrite) |
| Composer | 2.x | Gestão de dependências PHP |
| Bootstrap | 4.6.2 | Framework CSS responsivo |
| Font Awesome / Icofont | — | Ícones |
| DataTables | 1.13.7 (+ Responsive 2.5.0) | Tabelas interativas com pesquisa, ordenação e paginação em português |
| Highcharts | — | Gráficos e estatísticas |
| PHPMailer | 6.x | Envio de e-mails (recuperação de senha, credenciais de acesso) |

---

## Pré-requisitos

- **PHP 8.2 ou superior** com as extensões: `pdo`, `pdo_mysql`, `mbstring`, `gd`, `fileinfo`
- **MySQL 8.0+ ou MariaDB 10.4+**
- **Apache 2.4+** com `mod_rewrite` ativado
- **Composer 2.x** — [getcomposer.org](https://getcomposer.org)

> **Recomendado no Windows:** [XAMPP](https://www.apachefriends.org) inclui PHP, MySQL e Apache numa única instalação.

---

## Instalação Passo a Passo

### 1. Obter o código

```bash
git clone <url-do-repositorio> oficina-de-informatica
cd oficina-de-informatica
```

Ou copiar a pasta do projeto para o diretório do servidor:

```
C:\xampp\htdocs\oficina-de-informatica\
```

### 2. Instalar as dependências PHP

```bash
composer install
```

### 3. Configurar as variáveis de ambiente

```bash
# Linux / macOS / Git Bash
cp .env.example .env

# Windows PowerShell
Copy-Item .env.example .env
```

Editar o ficheiro `.env`:

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

# SMTP — envio de e-mails
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=
SMTP_PASS=
```

### 4. Criar a base de dados

```sql
CREATE DATABASE manutencao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Importar o schema institucional (fonte de verdade única — cria todas as tabelas, views e o utilizador Gerente inicial):

```bash
mysql -u root manutencao < database/schema.sql
```

### 5. Ativar o mod_rewrite no Apache

1. Abrir `C:\xampp\apache\conf\httpd.conf`
2. Remover o `#` de `#LoadModule rewrite_module`
3. Alterar `AllowOverride None` para `AllowOverride All` no bloco `<Directory "...htdocs">`
4. Reiniciar o Apache

### 6. Verificar a instalação

```
http://localhost/oficina-de-informatica/health.php
```

### 7. Iniciar a aplicação

```
http://localhost/oficina-de-informatica/
```

---

## Credenciais Padrão

| Papel | E-mail | Senha | Origem |
|-------|--------|-------|--------|
| Gerente de TI | `gerente.ti@ula.co.ao` | `Lusiada@2026` | `database/schema.sql` |

> **Altere esta senha imediatamente após o primeiro acesso**, através da opção "Esqueci a minha senha" no ecrã de login (requer SMTP configurado) ou diretamente na base de dados.

---

## Papéis de Acesso

| Papel | Descrição | Acesso |
|-------|-----------|--------|
| `gerente` | Gerente de TI | Acesso total: cadastros, manutenção, stock, relatórios, configurações |
| `tecnico` | Técnico de Informática | Ocorrências, Diagnósticos, Execuções, Planeamento, Equipamentos, Histórico, Perfil |

O controlo de acesso é feito em `core/Permissao.php`: além de exigir sessão iniciada, valida que rotas exclusivas do Gerente não sejam acedidas por um Técnico, mesmo por URL direta.

---

## Estrutura do Projeto

```
oficina-de-informatica/
├── app/
│   └── adms/
│       ├── Controllers/     # Um controlador por módulo
│       ├── Models/          # Lógica de acesso a dados
│       └── Views/           # Templates por módulo
├── core/
│   ├── Config.php           # Carrega variáveis do .env
│   ├── ConfigController.php # Router principal + constantes institucionais
│   ├── ConfigView.php       # Renderização de layouts
│   └── Permissao.php        # Controlo de acesso por papel
├── database/
│   └── schema.sql           # Schema institucional completo (fonte de verdade)
├── vendor/                  # Dependências Composer (gerado)
├── cron_planeamento.php     # Gatilho de agendamento do planeamento preventivo
├── health.php               # Health check endpoint
└── index.php                # Ponto de entrada da aplicação
```

---

## Fluxo da Aplicação

```
Browser → Apache (.htaccess) → index.php
                                    │
                            Core\ConfigController
                                    │
                          ┌─────────┴─────────┐
                    Core\Config          Core\Permissao
                    (carrega .env)   (valida sessão + papel)
                                    │
                          App\adms\Controllers\{Url}
                                    │
                          App\adms\Models\{Model}
                                    │
                          App\adms\Views\{view}.php
```

---

## Resolução de Problemas

| Problema | Solução |
|----------|---------|
| `Erro: Arquivo .env não encontrado` | Execute `cp .env.example .env` e configure |
| `SQLSTATE[HY000] [1049] Unknown database` | Crie a base de dados: `CREATE DATABASE manutencao` |
| `Class not found` | Execute `composer install` |
| Página em branco / erro 500 | Ative `DEBUG=true` no `.env` para ver o erro real |
| Redireciona sempre para login | Verifique se `mod_rewrite` está ativo |
| E-mail de recuperação não enviado | Configure `SMTP_USER`/`SMTP_PASS` reais no `.env` |

---

## Autor

**Josimar Ferreira**
- Email: josimardasilvaf36@gmail.com

Adaptação institucional para a Universidade Lusíada de Angola (v5.0.0) — ver `CHANGELOG.md` e `RELATORIO_TFC_ADAPTACAO.md` para o detalhe completo da transformação.

---

## Licença

Este projeto é de uso privado. Todos os direitos reservados.
