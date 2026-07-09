<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Equipamentos Abatidos (módulo 14) — workflow com aprovação:
 * Solicitado -> Aprovado (marca equipamento.estado='Abatido') | Rejeitado.
 */
class AdmsAbatimento extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosAbatimentos(): array {
        $stmt = $this->conn->prepare("
            SELECT a.*, e.numero_serie, e.marca, e.modelo,
                   us.nome AS solicitante_nome, us.sobrenome AS solicitante_sobrenome,
                   ua.nome AS aprovador_nome, ua.sobrenome AS aprovador_sobrenome
            FROM equipamentos_abatidos a
            INNER JOIN equipamento e ON e.idequipamento = a.id_equipamento
            LEFT JOIN usuario us ON us.idusuario = a.idusuario_solicitante
            LEFT JOIN usuario ua ON ua.idusuario = a.idusuario_aprovador
            ORDER BY a.data_solicitacao DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosEquipamentosParaAbater(): array {
        $stmt = $this->conn->prepare("
            SELECT idequipamento, numero_serie, marca, modelo
            FROM equipamento
            WHERE estado <> 'Abatido' OR estado IS NULL
            ORDER BY numero_serie
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function solicitarAbatimento(array $dados): bool {
        $idEquipamento = (int)$this->limparInput($dados['id_equipamento']);
        $motivo = $this->limparInput($dados['motivo']);
        $idSolicitante = $_SESSION['idlogado'] ?? null;

        $existente = $this->conn->prepare("SELECT idabatimento FROM equipamentos_abatidos WHERE id_equipamento=:id AND estado='Solicitado' LIMIT 1");
        $existente->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
        $existente->execute();
        if ($existente->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um pedido de abatimento pendente para este equipamento.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO equipamentos_abatidos (id_equipamento, motivo, idusuario_solicitante, estado, data_solicitacao)
            VALUES (:id_equipamento, :motivo, :idusuario_solicitante, 'Solicitado', NOW())
        ");
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':idusuario_solicitante', $idSolicitante, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Pedido de abatimento registado!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível registar o pedido.</div>';
        return false;
    }

    public function aprovarAbatimento(array $dados): bool {
        return $this->decidirAbatimento($dados, 'Aprovado');
    }

    public function rejeitarAbatimento(array $dados): bool {
        return $this->decidirAbatimento($dados, 'Rejeitado');
    }

    private function decidirAbatimento(array $dados, string $estado): bool {
        $id = (int)$this->limparInput($dados['idabatimento']);
        $idAprovador = $_SESSION['idlogado'] ?? null;
        $observacoes = !empty($dados['observacoes']) ? $this->limparInput($dados['observacoes']) : null;

        $abatimento = $this->conn->prepare("SELECT id_equipamento FROM equipamentos_abatidos WHERE idabatimento=:id LIMIT 1");
        $abatimento->bindParam(':id', $id, PDO::PARAM_INT);
        $abatimento->execute();
        $row = $abatimento->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }

        $stmt = $this->conn->prepare("
            UPDATE equipamentos_abatidos SET estado=:estado, idusuario_aprovador=:idusuario_aprovador, data_decisao=NOW(), observacoes=:observacoes
            WHERE idabatimento=:id
        ");
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idusuario_aprovador', $idAprovador, PDO::PARAM_INT);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            if ($estado === 'Aprovado') {
                $upd = $this->conn->prepare("UPDATE equipamento SET estado='Abatido' WHERE idequipamento=:id");
                $upd->bindParam(':id', $row['id_equipamento'], PDO::PARAM_INT);
                $upd->execute();
            }
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Pedido de abatimento ' . strtolower($estado) . '!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível decidir o pedido.</div>';
        return false;
    }
}
