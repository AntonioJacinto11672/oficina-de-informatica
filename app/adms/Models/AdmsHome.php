<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Indicadores do Dashboard — Gerente e Técnico. Sem qualquer indicador
 * financeiro/comercial, conforme o contexto institucional do sistema.
 */
class AdmsHome extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    public function index() {
        $this->dados = [];
        $this->dados['ocorrencias_preventivas'] = $this->dadosOcorrenciasPreventivas();

        if ($_SESSION['nivel'] === 'gerente') {
            $this->dadosHomeGerente();
        } elseif ($_SESSION['nivel'] === 'tecnico') {
            $this->dadosHomeTecnico();
        }

        return $this->dados;
    }

    private function contar(string $sql): int {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function dadosHomeGerente(): void {
        $this->dados['total_equipamentos'] = $this->contar("SELECT COUNT(*) FROM equipamento");
        $this->dados['total_tecnicos'] = $this->contar("SELECT COUNT(*) FROM tecnicos");
        $this->dados['total_ocorrencias'] = $this->contar("SELECT COUNT(*) FROM ocorrencias");
        $this->dados['total_diagnosticos'] = $this->contar("SELECT COUNT(*) FROM diagnostico");
        $this->dados['total_execucoes'] = $this->contar("SELECT COUNT(*) FROM execucao_manutencao");
        $this->dados['manutencoes_preventivas'] = $this->contar("SELECT COUNT(*) FROM ocorrencias WHERE categoria_manutencao = 'Preventiva'");
        $this->dados['manutencoes_corretivas'] = $this->contar("SELECT COUNT(*) FROM ocorrencias WHERE categoria_manutencao = 'Corretiva'");
        $this->dados['equipamentos_em_manutencao'] = $this->contar("SELECT COUNT(*) FROM equipamento WHERE estado = 'Em Manutenção'");
        $this->dados['equipamentos_disponiveis'] = $this->contar("SELECT COUNT(*) FROM equipamento WHERE estado = 'Disponível'");
        $this->dados['total_fornecedores'] = $this->contar("SELECT COUNT(*) FROM fornecedor");
        $this->dados['total_compras'] = $this->contar("SELECT COUNT(*) FROM compras WHERE data >= DATE_FORMAT(CURDATE(), '%Y-%m-01')");
        $this->dados['stock_baixo'] = $this->contar("SELECT COUNT(*) FROM produto WHERE estoque < estoque_minimo");
    }

    private function dadosHomeTecnico(): void {
        $idTecnico = (int)($_SESSION['idlogado'] ?? 0);

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM ocorrencias WHERE idtecnico_responsavel = :id AND estado NOT IN ('Concluída', 'Cancelada')");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['ocorrencias_atribuidas'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM ocorrencias WHERE idtecnico_responsavel = :id AND estado = 'Aberta'");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['diagnosticos_pendentes'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM execucao_manutencao WHERE idusuario_tecnico = :id AND estado = 'Em execução'");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['manutencoes_em_execucao'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM execucao_manutencao WHERE idusuario_tecnico = :id AND estado = 'Concluída' AND data_fim >= DATE_FORMAT(CURDATE(), '%Y-%m-01')");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['manutencoes_concluidas'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM plano_manutencao_preventiva WHERE idusuario_tecnico = :id AND ativo = 1");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['planeamentos_preventivos'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("
            SELECT COUNT(DISTINCT e.idequipamento) FROM equipamento e
            INNER JOIN ocorrencias o ON o.id_equipamento = e.idequipamento
            WHERE o.idtecnico_responsavel = :id AND e.estado = 'Em Manutenção'
        ");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['equipamentos_em_manutencao'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM ocorrencias WHERE idtecnico_responsavel = :id AND estado = 'Aguardando execução'");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['manutencoes_pendentes'] = (int)$stmt->fetchColumn();

        $stmt = $this->conn->prepare("
            SELECT o.idocorrencia, o.prioridade, o.estado, o.data_prevista, e.numero_serie, e.marca, e.modelo
            FROM ocorrencias o
            LEFT JOIN equipamento e ON e.idequipamento = o.id_equipamento
            WHERE o.idtecnico_responsavel = :id AND o.estado NOT IN ('Concluída', 'Cancelada')
            ORDER BY FIELD(o.prioridade, 'Urgente', 'Alta', 'Média', 'Baixa'), o.data_prevista ASC
        ");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        $this->dados['minhas_ocorrencias'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ocorrências preventivas em aberto + planos de manutenção preventiva a
     * vencer nos próximos 30 dias.
     */
    public function dadosOcorrenciasPreventivas(): array {
        $query = "
            SELECT o.data_prevista, e.numero_serie, e.marca, e.modelo, tm.nome AS tipo_manutencao, 'ocorrencia' AS origem
                FROM ocorrencias o
                LEFT JOIN equipamento e ON e.idequipamento = o.id_equipamento
                LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = o.id_tipo_manutencao
                WHERE o.categoria_manutencao = 'Preventiva'
                  AND o.estado NOT IN ('Concluída', 'Cancelada')
                  AND o.data_prevista BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
            UNION ALL
            SELECT p.proxima_execucao AS data_prevista, e.numero_serie, e.marca, e.modelo, tm.nome AS tipo_manutencao, 'plano' AS origem
                FROM plano_manutencao_preventiva p
                INNER JOIN equipamento e ON e.idequipamento = p.id_equipamento
                LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = p.id_tipo_manutencao
                WHERE p.ativo = 1
                  AND p.proxima_execucao BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
            ORDER BY data_prevista ASC
        ";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
