<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Diagnóstico Técnico (módulo 10) — histórico de diagnósticos ligado a uma
 * ocorrência concreta: descrição do problema, solução proposta, peças
 * solicitadas e encaminhamento para orçamento.
 */
class AdmsDiagnostico extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosDiagnosticos(): array {
        $stmt = $this->conn->prepare("
            SELECT d.*, e.numero_serie, e.marca, e.modelo,
                   u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome,
                   o.estado AS estado_ocorrencia
            FROM diagnostico d
            INNER JOIN equipamento e ON e.idequipamento = d.id_equipamento
            LEFT JOIN usuario u ON u.idusuario = d.idusuario_tecnico
            LEFT JOIN ocorrencias o ON o.idocorrencia = d.id_ocorrencia
            ORDER BY d.created DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosDiagnosticosDaOcorrencia($idocorrencia): array {
        $stmt = $this->conn->prepare("
            SELECT d.*, e.numero_serie, e.marca, e.modelo,
                   u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome
            FROM diagnostico d
            INNER JOIN equipamento e ON e.idequipamento = d.id_equipamento
            LEFT JOIN usuario u ON u.idusuario = d.idusuario_tecnico
            WHERE d.id_ocorrencia = :id
            ORDER BY d.created DESC
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosDiagnostico($iddiagnostico) {
        $stmt = $this->conn->prepare("SELECT * FROM diagnostico WHERE iddiagnostico = :id LIMIT 1");
        $stmt->bindParam(':id', $iddiagnostico, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cdsDiagnostico(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['id_ocorrencia']);
        $idEquipamento = (int)$this->limparInput($dados['id_equipamento']);
        $problema = !empty($dados['problema_descrito']) ? $this->limparInput($dados['problema_descrito']) : null;
        $solucao = !empty($dados['solucao_proposta']) ? $this->limparInput($dados['solucao_proposta']) : null;
        $pecas = !empty($dados['pecas_solicitadas']) ? $this->limparInput($dados['pecas_solicitadas']) : null;
        $idTecnico = $_SESSION['idlogado'] ?? null;

        $stmt = $this->conn->prepare("
            INSERT INTO diagnostico (id_ocorrencia, id_equipamento, idusuario_tecnico, problema_descrito, solucao_proposta, pecas_solicitadas, created)
            VALUES (:id_ocorrencia, :id_equipamento, :idusuario_tecnico, :problema_descrito, :solucao_proposta, :pecas_solicitadas, NOW())
        ");
        $stmt->bindParam(':id_ocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario_tecnico', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':problema_descrito', $problema);
        $stmt->bindParam(':solucao_proposta', $solucao);
        $stmt->bindParam(':pecas_solicitadas', $pecas);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->actualizarResumoEquipamento($idEquipamento, $problema, $solucao);

            // Um diagnóstico registado é, por si, evidência técnica — a ocorrência
            // avança para "Em diagnóstico" se ainda estiver só "Aberta".
            $ocorrencia = new \App\adms\Models\AdmsOcorrencia();
            $atual = $ocorrencia->dadosOcorrencia($idOcorrencia);
            if ($atual && $atual['estado'] === 'Aberta') {
                $ocorrencia->alterarEstado(['idocorrencia' => $idOcorrencia, 'estado' => 'Em diagnóstico', 'observacao' => 'Diagnóstico registado.']);
            }

            $_SESSION['msg'] = '<div class="alert alert-success text-center">Diagnóstico registado com sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível registar o diagnóstico.</div>';
        return false;
    }

    public function editDiagnostico(array $dados): bool {
        $id = (int)$this->limparInput($dados['iddiagnostico']);
        $existente = $this->dadosDiagnostico($id);
        if (!$existente || (int)$existente['encaminhado_orcamento'] === 1) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Este diagnóstico já foi encaminhado e não pode ser editado.</div>';
            return false;
        }

        $problema = !empty($dados['problema_descrito']) ? $this->limparInput($dados['problema_descrito']) : null;
        $solucao = !empty($dados['solucao_proposta']) ? $this->limparInput($dados['solucao_proposta']) : null;
        $pecas = !empty($dados['pecas_solicitadas']) ? $this->limparInput($dados['pecas_solicitadas']) : null;

        $stmt = $this->conn->prepare("
            UPDATE diagnostico SET problema_descrito=:problema_descrito, solucao_proposta=:solucao_proposta, pecas_solicitadas=:pecas_solicitadas
            WHERE iddiagnostico=:id
        ");
        $stmt->bindParam(':problema_descrito', $problema);
        $stmt->bindParam(':solucao_proposta', $solucao);
        $stmt->bindParam(':pecas_solicitadas', $pecas);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $this->actualizarResumoEquipamento((int)$existente['id_equipamento'], $problema, $solucao);

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Diagnóstico actualizado com sucesso!</div>';
        return true;
    }

    public function encaminharOrcamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['iddiagnostico']);
        $diagnostico = $this->dadosDiagnostico($id);
        if (!$diagnostico) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE diagnostico SET encaminhado_orcamento=1 WHERE iddiagnostico=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $ocorrencia = new \App\adms\Models\AdmsOcorrencia();
        $ocorrencia->alterarEstado([
            'idocorrencia' => $diagnostico['id_ocorrencia'],
            'estado' => 'Aguardando orçamento',
            'observacao' => 'Diagnóstico encaminhado para orçamento.',
        ]);

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Diagnóstico encaminhado para orçamento!</div>';
        return true;
    }

    private function actualizarResumoEquipamento(int $idEquipamento, ?string $problema, ?string $solucao): void {
        $resumo = trim(($problema ? "Problema: {$problema}. " : '') . ($solucao ? "Solução: {$solucao}." : ''));
        if ($resumo === '') {
            return;
        }
        $stmt = $this->conn->prepare("UPDATE equipamento SET diagnostico_tecnico=:resumo WHERE idequipamento=:id");
        $stmt->bindParam(':resumo', $resumo);
        $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
        $stmt->execute();
    }
}
