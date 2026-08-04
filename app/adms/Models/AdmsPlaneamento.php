<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Planeamento de Manutenção Preventiva (módulo 13).
 */
class AdmsPlaneamento extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosPlanos(): array {
        $stmt = $this->conn->prepare("
            SELECT p.*, e.numero_serie, e.marca, e.modelo, tm.nome AS tipo_manutencao,
                   u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome
            FROM plano_manutencao_preventiva p
            INNER JOIN equipamento e ON e.idequipamento = p.id_equipamento
            LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = p.id_tipo_manutencao
            LEFT JOIN usuario u ON u.idusuario = p.idusuario_tecnico
            ORDER BY p.proxima_execucao ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosPlanosPendentes(): array {
        $stmt = $this->conn->prepare("
            SELECT * FROM plano_manutencao_preventiva
            WHERE ativo = 1 AND proxima_execucao <= CURDATE()
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsPlano(array $dados): bool {
        $idEquipamento = (int)$this->limparInput($dados['id_equipamento']);
        $idTipoManutencao = !empty($dados['id_tipo_manutencao']) ? (int)$dados['id_tipo_manutencao'] : null;
        $idTecnico = !empty($dados['idusuario_tecnico']) ? (int)$dados['idusuario_tecnico'] : null;
        $periodicidade = max(1, (int)$this->limparInput($dados['periodicidade_dias']));
        $dataInicio = $this->limparInput($dados['data_inicio']);
        $observacoes = !empty($dados['observacoes']) ? $this->limparInput($dados['observacoes']) : null;

        $stmt = $this->conn->prepare("
            INSERT INTO plano_manutencao_preventiva (id_equipamento, id_tipo_manutencao, idusuario_tecnico, periodicidade_dias, data_inicio, proxima_execucao, observacoes, created)
            VALUES (:id_equipamento, :id_tipo_manutencao, :idusuario_tecnico, :periodicidade_dias, :data_inicio, :data_inicio, :observacoes, NOW())
        ");
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->bindParam(':id_tipo_manutencao', $idTipoManutencao, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario_tecnico', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':periodicidade_dias', $periodicidade, PDO::PARAM_INT);
        $stmt->bindParam(':data_inicio', $dataInicio);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Plano de manutenção preventiva criado!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível criar o plano.</div>';
        return false;
    }

    public function editPlano(array $dados): bool {
        $id = (int)$this->limparInput($dados['idplano']);
        $idTipoManutencao = !empty($dados['id_tipo_manutencao']) ? (int)$dados['id_tipo_manutencao'] : null;
        $idTecnico = !empty($dados['idusuario_tecnico']) ? (int)$dados['idusuario_tecnico'] : null;
        $periodicidade = max(1, (int)$this->limparInput($dados['periodicidade_dias']));
        $proximaExecucao = $this->limparInput($dados['proxima_execucao']);
        $observacoes = !empty($dados['observacoes']) ? $this->limparInput($dados['observacoes']) : null;

        $stmt = $this->conn->prepare("
            UPDATE plano_manutencao_preventiva
            SET id_tipo_manutencao=:id_tipo_manutencao, idusuario_tecnico=:idusuario_tecnico,
                periodicidade_dias=:periodicidade_dias, proxima_execucao=:proxima_execucao, observacoes=:observacoes
            WHERE idplano=:id
        ");
        $stmt->bindParam(':id_tipo_manutencao', $idTipoManutencao, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario_tecnico', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':periodicidade_dias', $periodicidade, PDO::PARAM_INT);
        $stmt->bindParam(':proxima_execucao', $proximaExecucao);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Plano actualizado com sucesso!</div>';
        return true;
    }

    public function toggleAtivo(array $dados): bool {
        $id = (int)$this->limparInput($dados['idplano']);
        $stmt = $this->conn->prepare("UPDATE plano_manutencao_preventiva SET ativo = NOT ativo WHERE idplano=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION['msg'] = '<div class="alert alert-success text-center">Estado do plano alterado!</div>';
        return true;
    }

    public function deletePlano(array $dados): bool {
        $id = (int)$this->limparInput($dados['idplano']);
        $stmt = $this->conn->prepare("DELETE FROM plano_manutencao_preventiva WHERE idplano=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Plano eliminado com sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar o plano.</div>';
        return false;
    }

    /**
     * Gera ocorrências 'Preventiva' para todos os planos activos vencidos, e
     * avança a próxima execução. Chamado manualmente (botão) ou por
     * cron_planeamento.php (agendado a nível de SO).
     */
    public function gerarOcorrenciasPendentes(): array {
        $pendentes = $this->dadosPlanosPendentes();
        $ocorrenciaModel = new AdmsOcorrencia();
        $geradas = 0;

        foreach ($pendentes as $plano) {
            $ocorrenciaModel->criarOcorrenciaPreventiva(
                (int)$plano['id_equipamento'],
                $plano['id_tipo_manutencao'] ? (int)$plano['id_tipo_manutencao'] : null,
                $plano['idusuario_tecnico'] ? (int)$plano['idusuario_tecnico'] : null,
                'Manutenção preventiva agendada: ' . ($plano['observacoes'] ?? 'sem observações.')
            );

            $stmt = $this->conn->prepare("UPDATE plano_manutencao_preventiva SET proxima_execucao = DATE_ADD(CURDATE(), INTERVAL periodicidade_dias DAY) WHERE idplano=:id");
            $stmt->bindParam(':id', $plano['idplano'], PDO::PARAM_INT);
            $stmt->execute();
            $geradas++;
        }

        return ["geradas" => $geradas];
    }
}
