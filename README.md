# Assistência Técnica Informática — Sistema de Gestão

Sistema web completo para gestão de assistências técnicas de equipamentos informáticos, desenvolvido em PHP com arquitectura MVC. Controla técnicos, clientes, equipamentos, orçamentos, serviços, estoque, finanças e gera relatórios em PDF.

---

## Funcionalidades

| Módulo | Descrição |
|--------|-----------|
| **Autenticação** | Login com controlo de sessão e níveis de acesso |
| **Recuperação de Senha** | Envio de código de 6 dígitos por email com validade de 30 min |
| **Técnicos** | Cadastro, edição e gestão com foto |
| **Recepcionistas** | Gestão da equipa de recepção |
| **Clientes** | Ficha completa do cliente |
| **Equipamentos** | Registo e histórico de equipamentos informáticos por cliente |
| **Entrada de Equipamentos** | Registo de entrada na oficina com alerta de retorno (180 dias) |
| **Fornecedores** | Pessoas singulares e colectivas |
| **Produtos & Estoque** | Controlo de stock com alertas de nível mínimo |
| **Categorias** | Organização de produtos |
| **Orçamentos** | Criação, aprovação e cancelamento com desconto configurável |
| **Serviços** | Registo de serviços executados por técnico |
| **Tipos de Serviço** | Catálogo de serviços disponíveis |
| **Vendas** | Histórico de vendas |
| **Compras** | Compras a fornecedores |
| **Contas a Pagar** | Controlo de vencimentos |
| **Contas a Receber** | Controlo de recebimentos de clientes |
| **Movimentação** | Fluxo de caixa (entradas e saídas) |
| **Comissões** | Cálculo automático de comissões dos técnicos |
| **Relatórios** | Exportação para PDF (serviços, vendas, compras, contas) |
| **Gráficos** | Dashboard visual com Chart.js |
| **Chat** | Comunicação interna entre utilizadores |

---

## Fluxo de Manutenção por Ocorrências

O sistema agora segue um fluxo de manutenção orientado a ocorrências, que liga o orçamento ao ciclo completo de atendimento do equipamento:

1. O equipamento é registado e associado ao cliente.
2. O técnico ou recepção cria uma ocorrência para esse equipamento, escolhendo o tipo de manutenção (Corretiva ou Preventiva) e a data prevista.
3. A ocorrência é ligada ao orçamento/ordem de serviço, permitindo acompanhar o estado da intervenção.
4. O técnico registra o diagnóstico, a descrição do trabalho e a estimativa de valor.
5. O orçamento pode ser aprovado, gerando automaticamente a conta a receber e o registo de entrada/saída do serviço.
6. Quando a intervenção estiver concluída, a ocorrência é encerrada e o estado é atualizado no sistema.
7. No dashboard aparecem as manutenções preventivas próximas, facilitando a agenda de intervenções.

Este modelo mantém o fluxo antigo de orçamentos, mas acrescenta uma camada de rastreio por ocorrência, o que torna o processo mais próximo do ciclo real de assistência técnica.

---

## Stack Tecnológica

| Componente | Versão | Função |
|-----------|--------|--------|
| PHP | 8.2+ | Backend / Lógica de negócio |
| MySQL | 8.0+ | Base de dados relacional |
| Apache | 2.4+ | Servidor web (com mod_rewrite) |
| Composer | 2.x | Gestão de dependências PHP |
| Bootstrap | 4.6.2 | Framework CSS responsivo |
| Font Awesome | 5.15.4 | Ícones |
| DataTables | 1.13.7 | Tabelas interactivas com pesquisa e paginação |
| Chart.js | — | Gráficos e dashboards visuais |
| PHPMailer | 6.x | Envio de emails |
| mPDF | 8.x | Geração de relatórios em PDF |

---

## Pré-requisitos

Antes de começar, certifique-se de que tem instalado:

- **PHP 8.2 ou superior** com as extensões: `pdo`, `pdo_mysql`, `mbstring`, `gd`, `fileinfo`
- **MySQL 8.0 ou superior**
- **Apache 2.4+** com `mod_rewrite` activado
- **Composer 2.x** — [getcomposer.org](https://getcomposer.org)

> **Recomendado no Windows:** [XAMPP](https://www.apachefriends.org) inclui PHP, MySQL e Apache numa única instalação.
>
> **Nota:** se aparecer um erro como `mpdf/mpdf requires ext-gd` ao executar `composer install`, habilite a extensão `gd` no ficheiro `php.ini` do PHP CLI e do servidor web.

---

## Instalação Passo a Passo

### 1. Clonar o repositório

```bash
git clone https://github.com/AntonioFerreira11672/oficina-de-informatica.git
cd oficina-de-informatica
```

Ou copiar a pasta do projecto para o directório do servidor:

```
# XAMPP
C:\xampp\htdocs\oficina-de-informatica\
```

---

### 2. Instalar as dependências PHP

```bash
composer install
```

Se receber este erro:

```text
mpdf/mpdf v8.3.1 requires ext-gd * -> it is missing from your system.
```

No Windows com XAMPP, o `php.ini` do PHP CLI e do Apache deve carregar a extensão `gd`.

1. Execute `php --ini` para ver o ficheiro de configuração carregado pelo PHP CLI.
2. Abra `C:\xampp\php\php.ini` e localize a linha:

```ini
;extension=gd
```

3. Remova o `;` para ativar a extensão:

```ini
extension=gd
```

4. Reinicie o Apache pelo XAMPP Control Panel.
5. Feche o terminal atual e abra um novo para garantir que o PHP CLI recarrega as definições.

Depois, execute novamente:

```bash
composer install
```

Isto instalará:
- `phpmailer/phpmailer` — para envio de emails
- `mpdf/mpdf` — para geração de PDFs

---

### 3. Configurar as variáveis de ambiente

Copiar o ficheiro de exemplo:

```bash
# Linux / macOS / Git Bash
cp .env.example .env

# Windows PowerShell
Copy-Item .env.example .env
```

Editar o ficheiro `.env` com os seus dados:

```ini
# === BASE DE DADOS ===
DB_HOST=localhost
DB_PORT=3306
DB_NAME=manutencao
DB_USER=root
DB_PASS=

# === APLICAÇÃO ===
APP_URL=http://localhost/oficina-de-informatica/
APP_NAME=ASSISTÊNCIA TÉCNICA INFORMÁTICA

# === DADOS DA OFICINA ===
OFFICE_ADDRESS=Luanda Rua da CTT, Rangel
OFFICE_EMAIL=josimardasilvaf36@gmail.com
OFFICE_PHONE=+244 931 950 857

# === NEGÓCIO ===
STOCK_LEVEL=5                  # Nível mínimo de stock (alerta)
DISCOUNT_ORC=SIM               # Activar desconto em orçamentos (SIM/NAO)
DISCOUNT_VALUE=0.05            # 5% de desconto
VALIDATE_QUOTE_DAYS=5          # Dias de validade de orçamento
DELETE_QUOTE_DAYS=15           # Dias para eliminar orçamentos abertos
TECHNICIAN_COMMISSION=SIM      # Activar comissões (SIM/NAO)
COMMISSION_VALUE=0.30          # 30% de comissão

# === DEBUG ===
DEBUG=false

# === SMTP — Recuperação de Senha (Mailtrap em desenvolvimento) ===
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=3e746e90bec3a6
SMTP_PASS=f24d1db0161205
```

---

### 4. Criar a base de dados

Aceder ao phpMyAdmin (`http://localhost/phpmyadmin`) ou usar o terminal MySQL:

```sql
CREATE DATABASE manutencao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Importar o schema da base de dados:

```bash
# Git Bash / Linux / macOS
mysql -u root -P 3308 manutencao < database/schema.sql

# Windows CMD
"C:\xampp\mysql\bin\mysql.exe" -u root -P 3308 manutencao < database\schema.sql
```

### 5. Aplicar as migrações da base de dados

Para aplicar as migrações incrementais do projecto, execute:

```bash
php migrate.php
```

Este comando lê os ficheiros em [database/migrations](database/migrations) e aplica apenas as alterações pendentes, registando-as para não serem executadas novamente.

Se precisar de carregar dados padrão adicionais, pode importar também:

```bash
# Windows CMD
"C:\xampp\mysql\bin\mysql.exe" -u root -P 3308 manutencao < database\seed_dados_padrao.sql
```

> Se aparecer `ERROR 1045 (28000): Plugin caching_sha2_password could not be loaded`, isso indica que o cliente MySQL não suporta o método de autenticação do servidor. Nesse caso:
>
> - Use um cliente MySQL 8 oficial em vez do cliente MariaDB do XAMPP
> - Ou altere o utilizador root para `mysql_native_password` no servidor MySQL
> - Uma alternativa é usar o comando `mysql --default-auth=mysql_native_password -u root -p manutencao < database/schema.sql` se o servidor suportar essa opção.

Importar a migração da base legada (opcional):

```bash
# Git Bash / Linux / macOS
mysql -u root -P 3308 manutencao < database/migrate_to_informatica.sql

# Windows CMD
"C:\xampp\mysql\bin\mysql.exe" -u root -P 3308 manutencao < database\migrate_to_informatica.sql
```

> A migração `database/migrate_to_informatica.sql` deve ser executada apenas numa base `manutencao` que contenha o esquema legadoo de `mecanicos`, `veiculo` e `entrada_veiculo`. Se essas tabelas já foram removidas ou a base já estiver atualizada, o comando falhará com um erro do tipo `Table 'manutencao.mecanicos' doesn't exist`.

O schema cria automaticamente todas as tabelas, 6 views e o utilizador administrador padrão.

Importar dados de demonstração adicionais (opcional — técnico padrão e tipos de serviço extra):

```bash
# Git Bash / Linux / macOS
mysql -u root manutencao < database/seed_dados_padrao.sql

# Windows CMD
"C:\xampp\mysql\bin\mysql.exe" -u root manutencao < database\seed_dados_padrao.sql
```

> Este script é seguro para executar mais do que uma vez (não duplica registos). Cria a conta de técnico `tecnico@gmail.com` e os tipos de serviço "Manutenção Preventiva" e "Manutenção Corretiva".

---

### 5. Activar o mod_rewrite no Apache

**XAMPP — Windows:**

1. Abrir `C:\xampp\apache\conf\httpd.conf`
2. Localizar `#LoadModule rewrite_module` e remover o `#`
3. Localizar `AllowOverride None` (dentro do bloco `<Directory "...htdocs">`) e alterar para `AllowOverride All`
4. Reiniciar o Apache no XAMPP Control Panel

---

### 6. Configurar Virtual Host (opcional, recomendado)

Adicionar virtual host no Apache (`httpd-vhosts.conf`):

```apache
<VirtualHost *:80>
    ServerName oficina-de-informatica.local
    DocumentRoot "C:/xampp/htdocs/oficina-de-informatica"
    <Directory "C:/xampp/htdocs/oficina-de-informatica">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Adicionar ao ficheiro de hosts (`C:\Windows\System32\drivers\etc\hosts`):

```
127.0.0.1   oficina-de-informatica.local
```

---

### 7. Verificar a instalação

Aceder ao health check para confirmar que tudo está a funcionar:

```
http://localhost/oficina-de-informatica/health.php
```

Resposta esperada:

```json
{
  "status": "ok",
  "app": "Sistema de Gestão de Assistência Técnica Informática",
  "version": "3.0.1",
  "php": "8.2.x",
  "timestamp": "2026-06-19 10:00:00",
  "checks": {
    "config": { "status": "ok", "message": "Ficheiro .env encontrado" },
    "dependencies": { "status": "ok", "message": "Dependências instaladas" },
    "database": { "status": "ok", "message": "Conexão com MySQL estabelecida" },
    "php_extensions": { "status": "ok", "message": "Todas as extensões necessárias estão activas" }
  }
}
```

---

### 8. Iniciar a aplicação

Aceder no browser:

```
http://localhost/oficina-de-informatica/
```

O sistema redireccionará automaticamente para a página de login.

---

## Credenciais Padrão

| Perfil | Email | Senha | Nível | Origem |
|--------|-------|-------|-------|--------|
| Administrador | `josimardasilvaf36@gmail.com` | `12345` (MD5: `827ccb0eea8a706c4c34a16891f84e7b`) | `adimin` | `database/schema.sql` |
| Técnico (demonstração) | `tecnico@gmail.com` | `tecnico123` | `tecnico` | `database/seed_dados_padrao.sql` (opcional) |

> Altere a senha após o primeiro acesso no menu **Perfil**.

---

## Níveis de Acesso

| Nível | Descrição | Acesso |
|-------|-----------|--------|
| `adimin` | Administrador / Gerente | Acesso total ao sistema |
| `tecnico` | Técnico de Informática | Orçamentos, serviços, comissões |
| `recep` | Recepcionista | Clientes, equipamentos, contas, orçamentos |

---

## Health Check

O endpoint `/health.php` verifica o estado da aplicação em tempo real:

| Check | O que verifica |
|-------|---------------|
| `config` | Existência do ficheiro `.env` |
| `dependencies` | Pasta `vendor/` e `autoload.php` presentes |
| `database` | Conexão PDO com MySQL |
| `php_extensions` | Extensões `pdo`, `pdo_mysql`, `mbstring`, `gd`, `fileinfo` |

**Códigos de resposta HTTP:**

| Código | Significado |
|--------|-------------|
| `200` | Tudo operacional (`status: "ok"`) |
| `200` | Degradado mas funcional (`status: "degraded"`) |
| `503` | Erro crítico — base de dados ou dependências em falta (`status: "error"`) |

---

## Documentação Interactiva (Swagger)

Aceder à documentação Swagger UI:

```
http://localhost/oficina-de-informatica/docs.php
```

O ficheiro OpenAPI 3.0 está disponível em:

```
http://localhost/oficina-de-informatica/swagger.json
```

---

## Estrutura do Projecto

```
oficina-de-informatica/
├── app/
│   └── adms/
│       ├── Controllers/     # 32 controladores (um por módulo)
│       ├── Models/          # Modelos com lógica de negócio
│       └── Views/           # Templates HTML por módulo
├── core/
│   ├── Config.php           # Carrega variáveis do .env
│   ├── ConfigController.php # Router principal + constantes globais
│   ├── ConfigView.php       # Renderização de layouts
│   └── Permissao.php        # Controlo de acesso por sessão
├── database/
│   ├── schema.sql           # Schema completo (tabelas, views, dados iniciais)
│   ├── seed_dados_padrao.sql # Dados de demonstração opcionais (técnico + tipos de serviço)
│   └── migrate_to_informatica.sql  # Migração de BD existente (mecanica → manutencao)
├── vendor/                  # Dependências Composer (gerado)
├── .env                     # Configuração local (não commitado)
├── .env.example             # Exemplo de configuração
├── .htaccess                # Rewrite rules Apache
├── composer.json            # Dependências PHP
├── docs.php                 # Swagger UI
├── health.php               # Health check endpoint
├── index.php                # Entry point da aplicação
└── swagger.json             # Especificação OpenAPI 3.0
```

---

## Como Usar o Sistema

Depois de instalar e aceder à aplicação, consulte o **[Manual de Utilização](MANUAL_UTILIZACAO.md)** — um guia passo-a-passo de todos os módulos: login, gestão de técnicos e recepcionistas, clientes e equipamentos, orçamentos e serviços, produtos e estoque, fornecedores e compras, contas a pagar/receber, comissões e relatórios.

---

## Fluxo da Aplicação

```
Browser → Apache (.htaccess) → index.php
                                    │
                            Core\ConfigController
                                    │
                          ┌─────────┴─────────┐
                    Core\Config          Core\Permissao
                    (carrega .env)       (valida sessão)
                                    │
                          App\adms\Controllers\{Url}
                                    │
                          App\adms\Models\{Model}
                                    │
                          App\adms\Views\{view}.php
```

---

## Configurações de Negócio

| Variável | Padrão | Descrição |
|----------|--------|-----------|
| `STOCK_LEVEL` | `5` | Quantidade mínima para alerta de stock baixo |
| `DISCOUNT_ORC` | `SIM` | Activar desconto automático em orçamentos |
| `DISCOUNT_VALUE` | `0.05` | Percentagem de desconto (5%) |
| `VALIDATE_QUOTE_DAYS` | `5` | Dias de validade de um orçamento |
| `DELETE_QUOTE_DAYS` | `15` | Dias para eliminar orçamentos abertos automaticamente |
| `TECHNICIAN_COMMISSION` | `SIM` | Activar sistema de comissões dos técnicos |
| `COMMISSION_VALUE` | `0.30` | Percentagem de comissão dos técnicos (30%) |

---

## Resolução de Problemas

| Problema | Solução |
|----------|---------|
| `Erro: Arquivo .env não encontrado` | Execute `cp .env.example .env` e configure |
| `SQLSTATE[HY000] [1049] Unknown database` | Crie a base de dados: `CREATE DATABASE manutencao` |
| `Class not found` | Execute `composer install` |
| Página em branco / erro 500 | Active `DEBUG=true` no `.env` para ver erros |
| Redirecciona sempre para login | Verifique se `mod_rewrite` está activo |
| PDFs não geram | Verifique se a extensão `gd` e `mbstring` estão activas |
| `composer install` falha com `mpdf/mpdf requires ext-gd` | Habilite `extension=gd` no `php.ini` do PHP CLI e do Apache, e reinicie o servidor web |
| Email de recuperação não enviado | Verifique as credenciais Mailtrap em `SMTP_USER` e `SMTP_PASS` no `.env` |

---

## Autor

**Josimar Ferreira**
- Email: josimardasilvaf36@gmail.com
- GitHub: [AntonioFerreira11672](https://github.com/AntonioFerreira11672)

---

## Licença

Este projecto é de uso privado. Todos os direitos reservados.
