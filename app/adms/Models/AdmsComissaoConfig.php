<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Configuração de Comissões (módulo 16) — percentagem por técnico e/ou
 * tipo de serviço, com fallback para a linha global e, na ausência de
 * qualquer configuração, para a constante .env VALOR_COMISSAO.
 */
class AdmsComissaoConfig extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosConfiguracoes(): array {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome, ts.nome AS tipo_servico
            FROM comissao_config c
            LEFT JOIN usuario u ON u.idusuario = c.idusuario_tecnico
            LEFT JOIN tipo_servico ts ON ts.idtipo_servico = c.id_tipo_servico
            ORDER BY (c.idusuario_tecnico IS NULL), (c.id_tipo_servico IS NULL), c.created DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsConfiguracao(array $dados): bool {
        $idTecnico = !empty($dados['idusuario_tecnico']) ? (int)$dados['idusuario_tecnico'] : null;
        $idTipoServico = !empty($dados['id_tipo_servico']) ? (int)$dados['id_tipo_servico'] : null;
        $percentual = (float)str_replace(',', '.', $this->limparInput($dados['percentual']));

        $stmt = $this->conn->prepare("
            INSERT INTO comissao_config (idusuario_tecnico, id_tipo_servico, percentual, ativo, created)
            VALUES (:idusuario_tecnico, :id_tipo_servico, :percentual, 1, NOW())
        ");
        $stmt->bindParam(':idusuario_tecnico', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':id_tipo_servico', $idTipoServico, PDO::PARAM_INT);
        $stmt->bindParam(':percentual', $percentual);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Configuração de comissão criada!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível criar a configuração.</div>';
        return false;
    }

    public function editConfiguracao(array $dados): bool {
        $id = (int)$this->limparInput($dados['idconfig']);
        $percentual = (float)str_replace(',', '.', $this->limparInput($dados['percentual']));

        $stmt = $this->conn->prepare("UPDATE comissao_config SET percentual=:percentual WHERE idconfig=:id");
        $stmt->bindParam(':percentual', $percentual);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Configuração actualizada!</div>';
        return true;
    }

    public function toggleAtivo(array $dados): bool {
        $id = (int)$this->limparInput($dados['idconfig']);
        $stmt = $this->conn->prepare("UPDATE comissao_config SET ativo = NOT ativo WHERE idconfig=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION['msg'] = '<div class="alert alert-success text-center">Estado alterado!</div>';
        return true;
    }

    public function deleteConfiguracao(array $dados): bool {
        $id = (int)$this->limparInput($dados['idconfig']);
        $stmt = $this->conn->prepare("DELETE FROM comissao_config WHERE idconfig=:id AND idusuario_tecnico IS NOT NULL OR id_tipo_servico IS NOT NULL");
        // Protege a linha global (idusuario_tecnico e id_tipo_servico ambos NULL) de ser eliminada por engano.
        $check = $this->conn->prepare("SELECT idusuario_tecnico, id_tipo_servico FROM comissao_config WHERE idconfig=:id");
        $check->bindParam(':id', $id, PDO::PARAM_INT);
        $check->execute();
        $row = $check->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['idusuario_tecnico'] === null && $row['id_tipo_servico'] === null) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">A configuração global não pode ser eliminada (edite-a em vez disso).</div>';
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM comissao_config WHERE idconfig=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Configuração eliminada!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar.</div>';
        return false;
    }

    /**
     * Percentagem efectiva (0.0–1.0) para um técnico + tipo de serviço:
     * técnico+serviço > técnico (qualquer serviço) > serviço (qualquer técnico)
     * > global > .env VALOR_COMISSAO.
     */
    public function obterPercentualEfetivo(?int $idUsuarioTecnico, ?int $idTipoServico): float {
        $stmt = $this->conn->prepare("
            SELECT percentual,
                   (idusuario_tecnico = :idusuario1 AND id_tipo_servico = :idtiposervico1) AS match_exato,
                   (idusuario_tecnico = :idusuario2 AND id_tipo_servico IS NULL) AS match_tecnico,
                   (idusuario_tecnico IS NULL AND id_tipo_servico = :idtiposervico2) AS match_servico,
                   (idusuario_tecnico IS NULL AND id_tipo_servico IS NULL) AS match_global
            FROM comissao_config
            WHERE ativo = 1
              AND (
                    (idusuario_tecnico = :idusuario3 AND id_tipo_servico = :idtiposervico3)
                 OR (idusuario_tecnico = :idusuario4 AND id_tipo_servico IS NULL)
                 OR (idusuario_tecnico IS NULL AND id_tipo_servico = :idtiposervico4)
                 OR (idusuario_tecnico IS NULL AND id_tipo_servico IS NULL)
              )
            ORDER BY match_exato DESC, match_tecnico DESC, match_servico DESC, match_global DESC
            LIMIT 1
        ");
        $stmt->bindValue(':idusuario1', $idUsuarioTecnico, PDO::PARAM_INT);
        $stmt->bindValue(':idtiposervico1', $idTipoServico, PDO::PARAM_INT);
        $stmt->bindValue(':idusuario2', $idUsuarioTecnico, PDO::PARAM_INT);
        $stmt->bindValue(':idtiposervico2', $idTipoServico, PDO::PARAM_INT);
        $stmt->bindValue(':idusuario3', $idUsuarioTecnico, PDO::PARAM_INT);
        $stmt->bindValue(':idtiposervico3', $idTipoServico, PDO::PARAM_INT);
        $stmt->bindValue(':idusuario4', $idUsuarioTecnico, PDO::PARAM_INT);
        $stmt->bindValue(':idtiposervico4', $idTipoServico, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && isset($row['percentual'])) {
            return ((float)$row['percentual']) / 100;
        }

        return defined('VALOR_COMISSAO') ? (float)VALOR_COMISSAO : 0.30;
    }
}
