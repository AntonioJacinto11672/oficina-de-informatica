# Guia de Execução — Sistema de Gestão de Manutenção de Equipamentos Informáticos (ULA)

## 1. Configuração Inicial

### Copiar ficheiro de exemplo
```bash
# Linux / macOS / Git Bash
cp .env.example .env

# Windows PowerShell
Copy-Item .env.example .env
```

### Editar configurações
Abra o ficheiro `.env` e ajuste conforme o seu ambiente:

```ini
DB_HOST=localhost
DB_PORT=3306
DB_NAME=manutencao
DB_USER=root
DB_PASS=

APP_URL=http://localhost/oficina-de-informatica/
APP_NAME=Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos da Universidade Lusíada de Angola
```

---

## 2. Instalar Dependências

```bash
composer install
```

Se a extensão `gd` não estiver activa:
```bash
composer install --ignore-platform-req=ext-gd
```

---

## 3. Criar e Popular a Base de Dados

```bash
# Criar a base de dados
mysql -u root -e "CREATE DATABASE manutencao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Aplicar o schema institucional (Git Bash / Linux / macOS) — fonte de verdade única
mysql -u root manutencao < database/schema.sql

# Windows CMD / PowerShell
"C:\xampp\mysql\bin\mysql.exe" -u root manutencao < database\schema.sql
```

> Em ambiente Windows, evite `Get-Content | mysql.exe` quando existir erro do plugin `caching_sha2_password.dll`. Use o redirecionamento direto para garantir que o cliente MySQL usa os ficheiros de plugin corretos.
>
> Se ainda vir `ERROR 1045 (28000): Plugin caching_sha2_password could not be loaded`, é sinal de incompatibilidade entre o cliente e o servidor MySQL/MariaDB. Use um cliente MySQL 8 compatível ou altere o método de autenticação do servidor para `mysql_native_password`.

O `schema.sql` já cria o utilizador Gerente inicial e os dados base (departamentos, categorias de equipamento, tipos de manutenção). Não são necessários scripts de seed adicionais numa instalação de raiz.

---

## 4. Validar Configuração

```bash
php test-config.php
```

Resultado esperado:
```
=== TESTE DE CONFIGURAÇÃO DO PROJETO ===
1. CARREGANDO CONFIGURAÇÕES:
   ✓ Arquivo .env carregado com sucesso
2. VERIFICANDO DEPENDÊNCIAS:
   ✓ Composer autoloader encontrado
   - PHPMailer: ✓
   - mPDF: ✓
3. TESTANDO CONEXÃO COM BANCO DE DADOS:
   ✓ Conexão com MySQL bem-sucedida
=== FIM DO TESTE ===
```

---

## 5. Acessar a Aplicação

| URL | Descrição |
|-----|-----------|
| `http://localhost/oficina-de-informatica/` | Aplicação principal |
| `http://localhost/oficina-de-informatica/health.php` | Estado do sistema |
| `http://localhost/oficina-de-informatica/docs.php` | Documentação Swagger UI |

**Credenciais padrão:**

| Papel | Email | Senha |
|-------|-------|-------|
| Gerente de TI | `gerente.ti@ula.co.ao` | `Lusiada@2026` |

> Altere esta senha imediatamente após o primeiro acesso.

> Para o guia completo de utilização de cada módulo do sistema, consulte **[MANUAL_UTILIZACAO.md](MANUAL_UTILIZACAO.md)**.

---

## 6. Verificar Erros

```bash
# Activar modo debug no .env:
DEBUG=true

# Ver log de erros PHP (XAMPP)
tail -f C:\xampp\php\logs\php_error_log
```

---

## 7. Problemas Comuns

| Problema | Solução |
|----------|---------|
| `Arquivo .env não encontrado` | `cp .env.example .env` |
| `Unknown database 'manutencao'` | `CREATE DATABASE manutencao` |
| `Class not found` | `composer install` |
| Página em branco | Activar `DEBUG=true` no `.env` |
| Redirecciona para login | Verificar `mod_rewrite` Apache |
| PDFs não geram | Activar extensão `gd` no `php.ini` |
| Email não enviado | Verificar `SMTP_*` no `.env` |

---

## 8. Estrutura do Projecto

```
oficina-de-informatica/
├── index.php                  (Entry point)
├── .env                       (Configurações locais — NÃO commitado)
├── .env.example               (Exemplo de configuração)
├── composer.json
├── health.php                 (Health check)
├── docs.php                   (Swagger UI)
├── swagger.json               (OpenAPI 3.0 spec)
├── core/
│   ├── Config.php             (Carrega .env)
│   ├── ConfigController.php   (Router + constantes)
│   ├── ConfigView.php         (Renderização de views)
│   └── Permissao.php          (Controlo de acesso)
├── app/adms/
│   ├── Controllers/           (32 controllers)
│   ├── Models/                (Lógica de negócio)
│   └── Views/                 (Templates HTML)
├── database/
│   ├── schema.sql             (Schema institucional completo — fonte de verdade)
│   └── migrations/            (Migrações futuras incrementais sobre este schema)
└── vendor/                    (Composer)
```

---

## 9. Verificação Rápida

```bash
php -v                          # PHP 8.2+
composer --version              # Composer 2.x
php test-config.php             # Valida configuração
php validate-project.php        # Validação completa
```

---

*Versão: 5.0.0 | Atualizado: 04 de Agosto de 2026*
