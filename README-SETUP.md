# Projecto Pronto para Uso — v3.0.1

## Resumo Executivo

**Sistema de Gestão de Assistência Técnica Informática** — configurado e validado.

### Status: Pronto para Desenvolvimento

---

## O Que Foi Feito

### 1. Dependências Instaladas
- **PHPMailer v6.12.0** — Envio de e-mails
- **mPDF v8.3.1** — Geração de PDFs

### 2. Configuração de Ambiente
- Ficheiro `.env` centralizado com todas as variáveis
- Classe `Config.php` para carregar variáveis de ambiente
- `ConfigController.php` actualizado para usar novas configurações
- `.env` protegido no `.gitignore`

### 3. Base de Dados
- BD: `manutencao`
- 27 tabelas criadas
- 6 views criadas
- Schema em `database/schema.sql`

---

## Resultados de Validação

| Categoria | Status | Detalhes |
|-----------|--------|----------|
| PHP | OK | v8.2 (compatível) |
| MySQL | OK | Conectado, BD `manutencao` |
| Extensões Obrigatórias | OK | PDO, JSON, cURL |
| Extensões Opcionais | OK | mbstring, fileinfo |
| Composer | OK | Instalado e a funcionar |
| Dependências | OK | PHPMailer, mPDF |
| Segurança | OK | `.env` protegido, `DEBUG=false` |

---

## Ficheiros Criados

| Ficheiro | Propósito |
|---------|----------|
| `.env` | Configurações da aplicação (LOCAL) |
| `.env.example` | Exemplo para documentação (GIT) |
| `core/Config.php` | Gerir variáveis de ambiente |
| `test-config.php` | Teste básico de configuração |
| `validate-project.php` | Validação completa |
| `health.php` | Health check endpoint |
| `docs.php` | Swagger UI |
| `swagger.json` | OpenAPI 3.0 spec |

---

## Próximos Passos

### Desenvolvimento Local
```bash
# 1. Validar configuração
php test-config.php

# 2. Aceder à aplicação
# http://localhost/oficina-de-equipamentos-informatico/
```

### Antes de Produção
- [ ] Alterar senha do MySQL no `.env`
- [ ] Configurar `APP_URL` com o domínio real
- [ ] `DEBUG=false`
- [ ] Configurar SSL/HTTPS
- [ ] Backups automáticos da BD
- [ ] Substituir credenciais Mailtrap por SMTP real

---

## Estrutura do Projecto

```
oficina-de-equipamentos-informatico/
├── index.php
├── .env                        (LOCAL — não commitado)
├── .env.example                (GIT)
├── composer.json
├── health.php
├── docs.php
├── swagger.json
├── core/
│   ├── Config.php
│   ├── ConfigController.php
│   ├── ConfigView.php
│   └── Permissao.php
├── app/adms/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── database/
│   ├── schema.sql
│   └── migrate_to_informatica.sql
└── vendor/
```

---

## Acesso Rápido

| URL | Descrição |
|-----|-----------|
| `/` | Aplicação (redireciona para login) |
| `/health.php` | Estado do sistema (JSON) |
| `/docs.php` | Swagger UI |
| `/test-config.php` | Validação de configuração |

**Credenciais padrão:** `antjacinto11672@gmail.com` / `12345`

---

*Versão: 3.0.1 | Atualizado: 19 de Junho de 2026*
