# CHANGELOG — Refatoração Completa do Sistema

## Versão 3.0.0 — Sistema de Gestão de Assistência Técnica Informática

**Data:** 2026-06-17  
**Autor:** António Jacinto  
**Descrição:** Transformação completa do sistema de gestão de oficina mecânica para sistema de gestão de assistência técnica informática. Toda a nomenclatura do projeto — classes, ficheiros, métodos, colunas de base de dados e rotas — foi atualizada para refletir o novo domínio.

---

## 1. Ficheiros PHP Renomeados

### Controllers (`app/adms/Controllers/`)
| Ficheiro Antigo | Ficheiro Novo | Classe Antiga | Classe Nova |
|---|---|---|---|
| `Mecanico.php` | `Tecnico.php` | `Mecanico` | `Tecnico` |
| `Veiculo.php` | `Equipamento.php` | `Veiculo` | `Equipamento` |
| `EntradaVeiculo.php` | `EntradaEquipamento.php` | `entradaVeiculo` | `EntradaEquipamento` |
| `RelatorioMecanica.php` | `RelatorioTecnico.php` | `RelatorioMecanica` | `RelatorioTecnico` |

### Models (`app/adms/Models/`)
| Ficheiro Antigo | Ficheiro Novo | Classe Antiga | Classe Nova |
|---|---|---|---|
| `AdmsMecanico.php` | `AdmsTecnico.php` | `AdmsMecanico` | `AdmsTecnico` |

---

## 2. Diretórios de Views Renomeados

| Diretório Antigo | Diretório Novo |
|---|---|
| `app/adms/Views/mecanico/` | `app/adms/Views/tecnico/` |
| `app/adms/Views/entradaVeiculo/` | `app/adms/Views/entradaEquipamento/` |
| `app/adms/Views/relatorio/mecanico/` | `app/adms/Views/relatorio/tecnico/` |

### Ficheiros de View Renomeados
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

## 3. Métodos PHP Renomeados

### `AdmsTecnico.php` (antes `AdmsMecanico.php`)
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

### `AdmsRecepcionista.php`
| Método Antigo | Método Novo |
|---|---|
| `dadosVeiculo()` | `dadosEquipamento()` |
| `cdsVeiculo()` | `cdsEquipamento()` |
| `deleteVeiculo()` | `deleteEquipamento()` |
| `editVeiculo()` | `editEquipamento()` |
| `valVeiculo()` | `valEquipamento()` |
| `valEditVeiculo()` | `valEditEquipamento()` |
| `entradaVeiculo()` | `entradaEquipamento()` |

### `AdmsPerfil.php`
| Método Antigo | Método Novo |
|---|---|
| `valEditMecananicos()` *(typo corrigido)* | `valEditTecnicos()` |
| `idMecanico()` | `idTecnico()` |

---

## 4. Rotas URL Renomeadas

| Rota Antiga (`?url=`) | Rota Nova (`?url=`) | Controller |
|---|---|---|
| `mecanico` | `tecnico` | `Tecnico` |
| `veiculo` | `equipamento` | `Equipamento` |
| `entradaVeiculo` | `entradaEquipamento` | `EntradaEquipamento` |
| `relatorioMecanica` | `relatorioTecnico` | `RelatorioTecnico` |

Ficheiro atualizado: `core/Permissao.php`

---

## 5. Base de Dados — Tabelas

| Tabela Antiga | Tabela Nova |
|---|---|
| `mecanicos` | `tecnicos` |

### Colunas Renomeadas
| Tabela | Coluna Antiga | Coluna Nova |
|---|---|---|
| `tecnicos` (ex `mecanicos`) | `idmecanicos` | `idtecnico` |
| `orcamentos` | `mecanico` | `tecnico` |
| `conntas_areceber` | `mecanico` | `tecnico` |
| `comissao` | `nifmecanico` | `niftecnico` |
| `entrada_equipamento` | `nifmecanico` | `niftecnico` |
| `entrada_veiculo` | `nifmecanico` | `niftecnico` |

### Valor Padrão da Coluna `usuario.nivel`
| Antes | Depois |
|---|---|
| `DEFAULT 'mecanico'` | `DEFAULT 'tecnico'` |

---

## 6. Valores de Sessão/Role Renomeados

| Valor Antigo | Valor Novo | Localização |
|---|---|---|
| `$_SESSION['usuario'] == 'mecanico'` | `== 'tecnico'` | Todos os ficheiros PHP |
| `nivel = 'mecanico'` (BD) | `nivel = 'tecnico'` | Tabela `usuario` |
| `value="Mecanico"` (form) | `value="Tecnico"` | Views e Controllers |

---

## 7. Constantes Renomeadas (`core/ConfigController.php`)

| Constante Antiga | Constante Nova |
|---|---|
| `COMISSAO_MECANICO` | `COMISSAO_TECNICO` |
| `MECHANIC_COMMISSION` (chave .env) | `TECHNICIAN_COMMISSION` |

**Mensagem `MENAGEM_RETORNO`:** atualizada para contexto de assistência técnica informática.

---

## 8. Nomes de Botões de Formulário Renomeados

| Nome Antigo | Nome Novo |
|---|---|
| `btnCdsMecanico` | `btnCdsTecnico` |
| `btnDeletMecanico` *(typo corrigido)* | `btnDeleteTecnico` |
| `btnEditMecanico` | `btnEditTecnico` |
| `btnEditPerfilMecanico` | `btnEditPerfilTecnico` |
| `btnCdsVeiculo` | `btnCdsEquipamento` |
| `btnEditveiculo` | `btnEditEquipamento` |
| `btnDeletVeiculo` *(typo corrigido)* | `btnDeleteEquipamento` |
| `btndeletVeiculo` *(typo corrigido)* | `btnDeleteEquipamento` |

---

## 9. Views da Base de Dados Atualizadas

| View | Alteração |
|---|---|
| `dadosorcamento` | `o.mecanico` → `o.tecnico`; alias `mecanico` mantido para compatibilidade |
| `dadosOrcamentosCompletoComProdutos` | `o.mecanico` → `o.tecnico`; alias mantido |
| `dadosClienteEquipamento` | Sem alteração (já correta) |
| `dadosClienteVeiculo` | Sem alteração (alias de compatibilidade) |

---

## 10. Outros Ficheiros Atualizados

| Ficheiro | Alteração |
|---|---|
| `composer.json` | `description` → "Sistema de Gestão de Assistência Técnica Informática" |
| `core/Permissao.php` | Array de rotas permitidas atualizado |
| `core/ConfigController.php` | Constantes renomeadas; mensagem MENAGEM_RETORNO atualizada |
| `database/schema.sql` | Schema completo atualizado (v3.0.0) |
| `database/migrate_to_informatica.sql` | Script de migração completo para BD existentes |

---

## 11. O Que NÃO Foi Alterado (Intencionalmente)

| Elemento | Motivo |
|---|---|
| Nome da BD: `mecanica` | Schema MySQL — requer dump/restore; não é necessário para funcionamento |
| Coluna `orcamentos.veiculo` | Legado — armazena `numero_serie`; renomear requer migração de dados complexa |
| Tabela `veiculo` | Mantida como alias de compatibilidade |
| Tabela `entrada_veiculo` | Mantida como alias de compatibilidade |
| Strings de sessão internas `'mecanico'` em SQL legacy | Convertidas para 'tecnico' via script de migração |

---

## 12. Como Migrar uma Base de Dados Existente

```sql
-- Execute no phpMyAdmin ou linha de comando MySQL:
source /caminho/para/database/migrate_to_informatica.sql
```

---

## Ficheiros Afetados (47 ficheiros PHP + schema)

Todos os ficheiros em `app/adms/Controllers/`, `app/adms/Models/`, `app/adms/Views/` e `core/` foram varridos e atualizados.
