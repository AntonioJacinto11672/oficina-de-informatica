<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Relatórios institucionais — Equipamentos, Técnicos, Ocorrências,
 * Diagnósticos, Manutenções, Planeamentos Preventivos, Histórico,
 * Fornecedores, Stock e Compras. Cada relatório devolve um título, o mapa
 * de colunas (chave => rótulo) e as linhas de dados, para serem
 * apresentados de forma genérica (impressão e exportação CSV).
 */
class AdmsRelatorio extends Conn {

    private $conn;

    public const TIPOS = [
        'equipamentos' => 'Equipamentos',
        'tecnicos' => 'Técnicos',
        'ocorrencias' => 'Ocorrências',
        'diagnosticos' => 'Diagnósticos',
        'manutencoes' => 'Manutenções (Execuções)',
        'planeamento' => 'Planeamentos Preventivos',
        'historico' => 'Histórico de Manutenções',
        'fornecedores' => 'Fornecedores',
        'stock' => 'Stock de Peças e Consumíveis',
        'compras' => 'Compras',
    ];

    public function __construct() {
        $this->conn = $this->connect();
    }

    public function obterRelatorio(string $tipo): array {
        switch ($tipo) {
            case 'equipamentos': return $this->relatorioEquipamentos();
            case 'tecnicos': return $this->relatorioTecnicos();
            case 'ocorrencias': return $this->relatorioOcorrencias();
            case 'diagnosticos': return $this->relatorioDiagnosticos();
            case 'manutencoes': return $this->relatorioManutencoes();
            case 'planeamento': return $this->relatorioPlaneamento();
            case 'historico': return $this->relatorioHistorico();
            case 'fornecedores': return $this->relatorioFornecedores();
            case 'stock': return $this->relatorioStock();
            case 'compras': return $this->relatorioCompras();
            default: return ['titulo' => 'Relatório', 'colunas' => [], 'linhas' => []];
        }
    }

    private function linhas(string $sql): array {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function relatorioEquipamentos(): array {
        return [
            'titulo' => self::TIPOS['equipamentos'],
            'colunas' => [
                'codigo' => 'Código', 'nome' => 'Nome', 'categoria_equipamento' => 'Tipo',
                'marca' => 'Marca', 'modelo' => 'Modelo', 'numero_serie' => 'Nº Série',
                'estado' => 'Estado', 'departamento' => 'Departamento', 'localizacao' => 'Localização',
            ],
            'filtros' => ['estado' => 'estado', 'categoria' => 'categoria_equipamento'],
            'linhas' => $this->linhas("SELECT codigo, nome, categoria_equipamento, marca, modelo, numero_serie, estado, departamento, localizacao FROM dadosEquipamento ORDER BY nome"),
        ];
    }

    private function relatorioTecnicos(): array {
        return [
            'titulo' => self::TIPOS['tecnicos'],
            'colunas' => ['nome' => 'Nome', 'sobrenome' => 'Sobrenome', 'email' => 'E-mail', 'telefone' => 'Telefone', 'st_conta' => 'Estado da Conta'],
            'filtros' => ['estado' => 'st_conta'],
            'linhas' => $this->linhas("SELECT t.nome, t.sobrenome, t.email, t.telefone, u.st_conta FROM tecnicos t LEFT JOIN usuario u ON u.nbi = t.nbi AND u.nif = t.nif AND u.nivel = 'tecnico' ORDER BY t.nome"),
        ];
    }

    private function relatorioOcorrencias(): array {
        return [
            'titulo' => self::TIPOS['ocorrencias'],
            'colunas' => [
                'idocorrencia' => '#', 'numero_serie' => 'Equipamento', 'tipo_manutencao' => 'Tipo',
                'categoria_manutencao' => 'Categoria', 'prioridade' => 'Prioridade', 'estado' => 'Estado',
                'tecnico_nome' => 'Técnico', 'data_abertura' => 'Aberta em', 'data_encerramento' => 'Encerrada em',
            ],
            'filtros' => ['estado' => 'estado', 'tecnico' => 'tecnico_nome', 'categoria' => 'categoria_manutencao', 'tipo' => 'tipo_manutencao', 'data' => 'data_abertura'],
            'linhas' => (new AdmsOcorrencia())->dadosOcorrencias(),
        ];
    }

    private function relatorioDiagnosticos(): array {
        return [
            'titulo' => self::TIPOS['diagnosticos'],
            'colunas' => [
                'created' => 'Data', 'numero_serie' => 'Equipamento', 'problema_descrito' => 'Problema',
                'solucao_proposta' => 'Solução Proposta', 'tecnico_nome' => 'Técnico',
            ],
            'filtros' => ['tecnico' => 'tecnico_nome', 'data' => 'created'],
            'linhas' => (new AdmsDiagnostico())->dadosDiagnosticos(),
        ];
    }

    private function relatorioManutencoes(): array {
        return [
            'titulo' => self::TIPOS['manutencoes'],
            'colunas' => [
                'idexecucao' => '#', 'estado' => 'Estado', 'tecnico_nome' => 'Técnico',
                'data_inicio' => 'Início', 'data_fim' => 'Fim',
            ],
            'filtros' => ['estado' => 'estado', 'tecnico' => 'tecnico_nome', 'data' => 'data_inicio'],
            'linhas' => (new AdmsExecucao())->dadosExecucoes(),
        ];
    }

    private function relatorioPlaneamento(): array {
        return [
            'titulo' => self::TIPOS['planeamento'],
            'colunas' => [
                'numero_serie' => 'Equipamento', 'tipo_manutencao' => 'Tipo', 'tecnico_nome' => 'Técnico',
                'periodicidade_dias' => 'Periodicidade (dias)', 'proxima_execucao' => 'Próxima Execução', 'ativo' => 'Ativo',
            ],
            'filtros' => ['tipo' => 'tipo_manutencao', 'tecnico' => 'tecnico_nome'],
            'linhas' => array_map(function ($p) {
                $p['ativo'] = $p['ativo'] ? 'Sim' : 'Não';
                return $p;
            }, (new AdmsPlaneamento())->dadosPlanos()),
        ];
    }

    private function relatorioHistorico(): array {
        return [
            'titulo' => self::TIPOS['historico'],
            'colunas' => [
                'idocorrencia' => '#', 'numero_serie' => 'Equipamento', 'categoria_manutencao' => 'Categoria',
                'estado' => 'Estado', 'tecnico_nome' => 'Técnico', 'data_encerramento' => 'Encerrada em',
            ],
            'filtros' => ['estado' => 'estado', 'tecnico' => 'tecnico_nome', 'categoria' => 'categoria_manutencao', 'data' => 'data_encerramento'],
            'linhas' => (new AdmsOcorrencia())->dadosHistoricoGeral(),
        ];
    }

    private function relatorioFornecedores(): array {
        return [
            'titulo' => self::TIPOS['fornecedores'],
            'colunas' => ['nome' => 'Nome', 'tipo_pessoa' => 'Tipo', 'nif' => 'NIF', 'telefone' => 'Telefone', 'email' => 'E-mail', 'morada' => 'Morada'],
            'filtros' => ['categoria' => 'tipo_pessoa'],
            'linhas' => (new AdmsFornecedor())->dadosFornecedores(),
        ];
    }

    private function relatorioStock(): array {
        return [
            'titulo' => self::TIPOS['stock'],
            'colunas' => ['nome' => 'Peça', 'referencia' => 'Referência', 'categoria' => 'Categoria', 'fornecedor' => 'Fornecedor', 'estoque' => 'Stock Atual', 'estoque_minimo' => 'Stock Mínimo'],
            'filtros' => ['categoria' => 'categoria'],
            'linhas' => (new AdmsProduto())->dadosProdutos(),
        ];
    }

    private function relatorioCompras(): array {
        return [
            'titulo' => self::TIPOS['compras'],
            'colunas' => ['data' => 'Data', 'produto' => 'Peça', 'fornecedor' => 'Fornecedor', 'quantidade' => 'Quantidade', 'usuario_nome' => 'Registado por'],
            'filtros' => ['data' => 'data'],
            'linhas' => (new AdmsCompras())->dadosCompras(),
        ];
    }
}
