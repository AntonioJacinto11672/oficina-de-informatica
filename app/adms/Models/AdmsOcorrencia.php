<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Gestão de Ocorrências (módulo 9) — ponto de entrada do fluxo de manutenção:
 * abertura, associação de equipamento(s), atribuição de técnico, diagnóstico,
 * orçamento, execução, encerramento.
 */
class AdmsOcorrencia extends Conn {

    private $conn;

    public const ESTADOS = [
        'Aberta',
        'Em diagnóstico',
        'Aguardando orçamento',
        'Aguardando aprovação',
        'Em manutenção',
        'Concluída',
        'Cancelada',
    ];

    public const ESTADOS_FINAIS = ['Concluída', 'Cancelada'];

    public const PRIORIDADES = ['Baixa', 'Média', 'Alta', 'Urgente'];

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    // ============================================================
    // Caminho legado — chamado a partir de AdmsTecnico::criarOcorrencia()/
    // atualizarOcorrencia() (sombra automática ao abrir/editar um orçamento).
    // Mantido tal como estava até a Fase 3 religar orçamentos à ocorrência.
    // ============================================================

    public function criarOcorrenciaDeOrcamento(array $dados, string $status = 'Aberta'): bool {
        if (empty($dados['veiculo']) && empty($dados['idorcamentos'])) {
            return false;
        }

        $idOrcamento = isset($dados['idorcamentos']) ? $this->limparInput($dados['idorcamentos']) : null;
        $numeroSerie = isset($dados['veiculo']) ? $this->limparInput($dados['veiculo']) : null;
        $idTipoServico = isset($dados['id_tipo_servico']) ? $this->limparInput($dados['id_tipo_servico']) : null;
        $tipoManutencao = isset($dados['tipo_manutencao']) && $dados['tipo_manutencao'] !== '' ? $this->limparInput($dados['tipo_manutencao']) : 'Corretiva';
        $descricao = isset($dados['descricao']) ? $this->limparInput($dados['descricao']) : null;
        $dataPrevista = isset($dados['data_prevista']) && $dados['data_prevista'] !== '' ? $this->limparInput($dados['data_prevista']) : null;
        $tecnico = isset($dados['tecnico']) && $dados['tecnico'] !== '' ? $this->limparInput($dados['tecnico']) : ($_SESSION['nif'] ?? null);
        $idEquipamento = $this->buscarIdEquipamentoPorNumeroSerie($numeroSerie);

        $query = "INSERT INTO ocorrencias (id_orcamento, id_equipamento, id_tipo_servico, tecnico, tipo_manutencao, descricao, estado, data_abertura, data_prevista, status, created)
                  VALUES (:id_orcamento, :id_equipamento, :id_tipo_servico, :tecnico, :tipo_manutencao, :descricao, :estado, CURDATE(), :data_prevista, :status, NOW())";
        $result = $this->conn->prepare($query);
        $result->bindParam(':id_orcamento', $idOrcamento);
        $result->bindParam(':id_equipamento', $idEquipamento);
        $result->bindParam(':id_tipo_servico', $idTipoServico);
        $result->bindParam(':tecnico', $tecnico);
        $result->bindParam(':tipo_manutencao', $tipoManutencao);
        $result->bindParam(':descricao', $descricao);
        $result->bindParam(':estado', $status);
        $result->bindParam(':data_prevista', $dataPrevista);
        $result->bindParam(':status', $status);
        $result->execute();

        if ($result->rowCount() > 0 && $idEquipamento) {
            $idOcorrencia = (int)$this->conn->lastInsertId();
            $this->vincularEquipamento($idOcorrencia, $idEquipamento);
            $this->registarHistorico($idOcorrencia, null, $status, null, 'Ocorrência criada automaticamente a partir do orçamento.');
        }

        return $result->rowCount() > 0;
    }

    public function atualizarOcorrenciaDeOrcamento(array $dados, string $status = 'Aberta'): bool {
        if (empty($dados['idorcamentos'])) {
            return false;
        }

        $idOrcamento = $this->limparInput($dados['idorcamentos']);
        $numeroSerie = isset($dados['veiculo']) ? $this->limparInput($dados['veiculo']) : null;
        $idTipoServico = isset($dados['id_tipo_servico']) ? $this->limparInput($dados['id_tipo_servico']) : null;
        $tipoManutencao = isset($dados['tipo_manutencao']) && $dados['tipo_manutencao'] !== '' ? $this->limparInput($dados['tipo_manutencao']) : 'Corretiva';
        $descricao = isset($dados['descricao']) ? $this->limparInput($dados['descricao']) : null;
        $dataPrevista = isset($dados['data_prevista']) && $dados['data_prevista'] !== '' ? $this->limparInput($dados['data_prevista']) : null;
        $tecnico = isset($dados['tecnico']) && $dados['tecnico'] !== '' ? $this->limparInput($dados['tecnico']) : ($_SESSION['nif'] ?? null);
        $observacoes = isset($dados['obs']) ? $this->limparInput($dados['obs']) : null;
        $idEquipamento = $this->buscarIdEquipamentoPorNumeroSerie($numeroSerie);

        $busca = $this->conn->prepare("SELECT idocorrencia, estado FROM ocorrencias WHERE id_orcamento = :id_orcamento LIMIT 1");
        $busca->bindParam(':id_orcamento', $idOrcamento);
        $busca->execute();
        $ocorrenciaExistente = $busca->fetch(PDO::FETCH_ASSOC);
        if (!$ocorrenciaExistente) {
            return $this->criarOcorrenciaDeOrcamento($dados, $status);
        }

        $query = "UPDATE ocorrencias SET id_equipamento=:id_equipamento, id_tipo_servico=:id_tipo_servico, tecnico=:tecnico, tipo_manutencao=:tipo_manutencao, descricao=:descricao, estado=:estado, data_prevista=:data_prevista, observacoes=:observacoes, status=:status";
        if (in_array($status, ['Concluído', 'Concluída', 'Encerrada', 'Entregue'], true)) {
            $query .= ", data_encerramento=CURDATE()";
        } else {
            $query .= ", data_encerramento=NULL";
        }
        $query .= " WHERE id_orcamento=:id_orcamento";

        $result = $this->conn->prepare($query);
        $result->bindParam(':id_equipamento', $idEquipamento);
        $result->bindParam(':id_tipo_servico', $idTipoServico);
        $result->bindParam(':tecnico', $tecnico);
        $result->bindParam(':tipo_manutencao', $tipoManutencao);
        $result->bindParam(':descricao', $descricao);
        $result->bindParam(':estado', $status);
        $result->bindParam(':data_prevista', $dataPrevista);
        $result->bindParam(':observacoes', $observacoes);
        $result->bindParam(':status', $status);
        $result->bindParam(':id_orcamento', $idOrcamento);
        $result->execute();

        if ($result->rowCount() > 0) {
            if ($idEquipamento) {
                $this->vincularEquipamento((int)$ocorrenciaExistente['idocorrencia'], $idEquipamento);
            }
            if ($ocorrenciaExistente['estado'] !== $status) {
                $this->registarHistorico((int)$ocorrenciaExistente['idocorrencia'], $ocorrenciaExistente['estado'], $status, null, 'Estado actualizado a partir do orçamento.');
            }
        }

        return $result->rowCount() > 0;
    }

    private function buscarIdEquipamentoPorNumeroSerie(?string $numeroSerie): ?int {
        if (empty($numeroSerie)) {
            return null;
        }
        $resultEquip = $this->conn->prepare("SELECT idequipamento FROM equipamento WHERE numero_serie = :numero_serie LIMIT 1");
        $resultEquip->bindParam(':numero_serie', $numeroSerie);
        $resultEquip->execute();
        $equipamento = $resultEquip->fetch(PDO::FETCH_ASSOC);
        return $equipamento ? (int)$equipamento['idequipamento'] : null;
    }

    private function vincularEquipamento(int $idOcorrencia, int $idEquipamento): void {
        $stmt = $this->conn->prepare("INSERT IGNORE INTO ocorrencia_equipamento (id_ocorrencia, id_equipamento) VALUES (:id_ocorrencia, :id_equipamento)");
        $stmt->bindParam(':id_ocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Cria uma ocorrência 'Preventiva' a partir de um plano de manutenção
     * vencido (chamado por AdmsPlaneamento::gerarOcorrenciasPendentes()).
     */
    public function criarOcorrenciaPreventiva(int $idEquipamento, ?int $idTipoServico, ?int $idTecnico, ?string $observacao): int {
        $tecnicoNif = $this->nifDoTecnico($idTecnico);

        $stmt = $this->conn->prepare("
            INSERT INTO ocorrencias (id_equipamento, id_tipo_servico, tecnico, idtecnico_responsavel, tipo_manutencao, prioridade, descricao, estado, data_abertura, data_prevista, status, created)
            VALUES (:id_equipamento, :id_tipo_servico, :tecnico, :idtecnico_responsavel, 'Preventiva', 'Média', :descricao, 'Aberta', CURDATE(), CURDATE(), 'Aberta', NOW())
        ");
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->bindParam(':id_tipo_servico', $idTipoServico, PDO::PARAM_INT);
        $stmt->bindParam(':tecnico', $tecnicoNif);
        $stmt->bindParam(':idtecnico_responsavel', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':descricao', $observacao);
        $stmt->execute();

        $idOcorrencia = (int)$this->conn->lastInsertId();
        $this->vincularEquipamento($idOcorrencia, $idEquipamento);
        $this->registarHistorico($idOcorrencia, null, 'Aberta', null, 'Ocorrência preventiva gerada automaticamente pelo planeamento.');

        return $idOcorrencia;
    }

    // ============================================================
    // Gestão independente de Ocorrências (ecrã dedicado)
    // ============================================================

    public function dadosOcorrencias(): array {
        $query = "
            SELECT
                o.idocorrencia, o.tipo_manutencao, o.prioridade, o.descricao, o.estado, o.status,
                o.data_abertura, o.data_prevista, o.data_encerramento, o.created,
                ts.nome AS tipo_servico,
                u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome,
                (SELECT COUNT(*) FROM ocorrencia_equipamento oe WHERE oe.id_ocorrencia = o.idocorrencia) AS total_equipamentos,
                (SELECT GROUP_CONCAT(e.numero_serie SEPARATOR ', ')
                    FROM ocorrencia_equipamento oe
                    INNER JOIN equipamento e ON e.idequipamento = oe.id_equipamento
                    WHERE oe.id_ocorrencia = o.idocorrencia) AS equipamentos_numero_serie
            FROM ocorrencias o
            LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico
            LEFT JOIN usuario u ON u.idusuario = o.idtecnico_responsavel
            ORDER BY o.created DESC
        ";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosOcorrencia($idocorrencia) {
        $stmt = $this->conn->prepare("
            SELECT o.*, ts.nome AS tipo_servico
            FROM ocorrencias o
            LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico
            WHERE o.idocorrencia = :id LIMIT 1
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function dadosEquipamentosDaOcorrencia($idocorrencia): array {
        $stmt = $this->conn->prepare("
            SELECT e.idequipamento, e.numero_serie, e.marca, e.modelo, e.tipo_equipamento, e.estado
            FROM ocorrencia_equipamento oe
            INNER JOIN equipamento e ON e.idequipamento = oe.id_equipamento
            WHERE oe.id_ocorrencia = :id
            ORDER BY e.numero_serie
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosEquipamentosDisponiveis(): array {
        $stmt = $this->conn->prepare("
            SELECT idequipamento, numero_serie, marca, modelo, tipo_equipamento
            FROM equipamento
            WHERE estado <> 'Abatido' OR estado IS NULL
            ORDER BY numero_serie
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosTecnicos(): array {
        $stmt = $this->conn->prepare("
            SELECT idusuario, nome, sobrenome
            FROM usuario
            WHERE nivel = 'tecnico' AND st_conta = 'Ativada'
            ORDER BY nome
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosTiposServico(): array {
        $stmt = $this->conn->prepare("SELECT idtipo_servico, nome FROM tipo_servico ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Dados para pré-preencher o formulário de "Novo Orçamento" a partir de
     * uma ocorrência: NIF do cliente + nº de série do equipamento principal.
     */
    public function dadosParaNovoOrcamento($idocorrencia) {
        $stmt = $this->conn->prepare("
            SELECT e.numero_serie, c.nif
            FROM ocorrencia_equipamento oe
            INNER JOIN equipamento e ON e.idequipamento = oe.id_equipamento
            LEFT JOIN clientes c ON c.idclientes = e.idcliente
            WHERE oe.id_ocorrencia = :id
            ORDER BY oe.idocorrencia_equipamento ASC
            LIMIT 1
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function dadosHistoricoOcorrencia($idocorrencia): array {
        $stmt = $this->conn->prepare("
            SELECT h.*, u.nome AS usuario_nome, u.sobrenome AS usuario_sobrenome
            FROM ocorrencia_historico h
            LEFT JOIN usuario u ON u.idusuario = h.idusuario
            WHERE h.id_ocorrencia = :id
            ORDER BY h.created ASC, h.idocorrencia_historico ASC
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsOcorrencia(array $dados): bool {
        $idTipoServico = !empty($dados['id_tipo_servico']) ? $this->limparInput($dados['id_tipo_servico']) : null;
        $tipoManutencao = !empty($dados['tipo_manutencao']) ? $this->limparInput($dados['tipo_manutencao']) : 'Corretiva';
        $prioridade = in_array($dados['prioridade'] ?? '', self::PRIORIDADES, true) ? $dados['prioridade'] : 'Média';
        $descricao = !empty($dados['descricao']) ? $this->limparInput($dados['descricao']) : null;
        $dataPrevista = !empty($dados['data_prevista']) ? $this->limparInput($dados['data_prevista']) : null;
        $idTecnico = !empty($dados['idtecnico_responsavel']) ? (int)$dados['idtecnico_responsavel'] : null;
        $tecnicoNif = $this->nifDoTecnico($idTecnico);
        $equipamentos = array_filter(array_map('intval', (array)($dados['equipamentos'] ?? [])));

        if (empty($equipamentos)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Selecciona pelo menos um equipamento.</div>';
            return false;
        }

        $query = "INSERT INTO ocorrencias
                    (id_equipamento, id_tipo_servico, tecnico, idtecnico_responsavel, tipo_manutencao, prioridade, descricao, estado, data_abertura, data_prevista, status, created)
                  VALUES
                    (:id_equipamento, :id_tipo_servico, :tecnico, :idtecnico_responsavel, :tipo_manutencao, :prioridade, :descricao, 'Aberta', CURDATE(), :data_prevista, 'Aberta', NOW())";
        $equipamentos = array_values($equipamentos);
        $primeiroEquipamento = $equipamentos[0];
        $result = $this->conn->prepare($query);
        $result->bindParam(':id_equipamento', $primeiroEquipamento, PDO::PARAM_INT);
        $result->bindParam(':id_tipo_servico', $idTipoServico);
        $result->bindParam(':tecnico', $tecnicoNif);
        $result->bindParam(':idtecnico_responsavel', $idTecnico, PDO::PARAM_INT);
        $result->bindParam(':tipo_manutencao', $tipoManutencao);
        $result->bindParam(':prioridade', $prioridade);
        $result->bindParam(':descricao', $descricao);
        $result->bindParam(':data_prevista', $dataPrevista);
        $result->execute();

        if ($result->rowCount() > 0) {
            $idOcorrencia = (int)$this->conn->lastInsertId();
            foreach ($equipamentos as $idEquipamento) {
                $this->vincularEquipamento($idOcorrencia, $idEquipamento);
            }
            $this->registarHistorico($idOcorrencia, null, 'Aberta', $_SESSION['idlogado'] ?? null, 'Ocorrência aberta.');
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Ocorrência aberta com sucesso!</div>';
            return true;
        }

        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível abrir a ocorrência.</div>';
        return false;
    }

    public function editOcorrencia(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['idocorrencia']);
        $idTipoServico = !empty($dados['id_tipo_servico']) ? $this->limparInput($dados['id_tipo_servico']) : null;
        $tipoManutencao = !empty($dados['tipo_manutencao']) ? $this->limparInput($dados['tipo_manutencao']) : 'Corretiva';
        $prioridade = in_array($dados['prioridade'] ?? '', self::PRIORIDADES, true) ? $dados['prioridade'] : 'Média';
        $descricao = !empty($dados['descricao']) ? $this->limparInput($dados['descricao']) : null;
        $dataPrevista = !empty($dados['data_prevista']) ? $this->limparInput($dados['data_prevista']) : null;
        $equipamentos = array_filter(array_map('intval', (array)($dados['equipamentos'] ?? [])));

        $query = "UPDATE ocorrencias SET id_tipo_servico=:id_tipo_servico, tipo_manutencao=:tipo_manutencao,
                    prioridade=:prioridade, descricao=:descricao, data_prevista=:data_prevista
                  WHERE idocorrencia=:idocorrencia";
        $result = $this->conn->prepare($query);
        $result->bindParam(':id_tipo_servico', $idTipoServico);
        $result->bindParam(':tipo_manutencao', $tipoManutencao);
        $result->bindParam(':prioridade', $prioridade);
        $result->bindParam(':descricao', $descricao);
        $result->bindParam(':data_prevista', $dataPrevista);
        $result->bindParam(':idocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $result->execute();

        if (!empty($equipamentos)) {
            $atuais = array_column($this->dadosEquipamentosDaOcorrencia($idOcorrencia), 'idequipamento');
            foreach (array_diff($equipamentos, $atuais) as $novo) {
                $this->vincularEquipamento($idOcorrencia, (int)$novo);
            }
            foreach (array_diff($atuais, $equipamentos) as $removido) {
                $del = $this->conn->prepare("DELETE FROM ocorrencia_equipamento WHERE id_ocorrencia=:id_ocorrencia AND id_equipamento=:id_equipamento");
                $del->bindValue(':id_ocorrencia', $idOcorrencia, PDO::PARAM_INT);
                $del->bindValue(':id_equipamento', (int)$removido, PDO::PARAM_INT);
                $del->execute();
            }
        }

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Ocorrência actualizada com sucesso!</div>';
        return true;
    }

    public function atribuirTecnico(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['idocorrencia']);
        $idTecnico = !empty($dados['idtecnico_responsavel']) ? (int)$dados['idtecnico_responsavel'] : null;
        $tecnicoNif = $this->nifDoTecnico($idTecnico);

        $stmt = $this->conn->prepare("UPDATE ocorrencias SET idtecnico_responsavel=:idtecnico_responsavel, tecnico=:tecnico WHERE idocorrencia=:idocorrencia");
        $stmt->bindParam(':idtecnico_responsavel', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':tecnico', $tecnicoNif);
        $stmt->bindParam(':idocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $nomeTecnico = $this->nomeDoTecnico($idTecnico) ?? 'não atribuído';
            $this->registarHistorico($idOcorrencia, null, null, $_SESSION['idlogado'] ?? null, "Técnico responsável definido: {$nomeTecnico}.");
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Técnico atribuído com sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atribuir o técnico.</div>';
        return false;
    }

    public function alterarEstado(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['idocorrencia']);
        $novoEstado = $dados['estado'] ?? '';
        $observacao = !empty($dados['observacao']) ? $this->limparInput($dados['observacao']) : null;

        if (!in_array($novoEstado, self::ESTADOS, true)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Estado inválido.</div>';
            return false;
        }

        $atual = $this->dadosOcorrencia($idOcorrencia);
        if (!$atual) {
            return false;
        }

        $query = "UPDATE ocorrencias SET estado=:estado, status=:estado";
        if (!empty($observacao)) {
            $query .= ", observacoes=:observacao";
        }
        $query .= in_array($novoEstado, self::ESTADOS_FINAIS, true) ? ", data_encerramento=CURDATE()" : ", data_encerramento=NULL";
        $query .= " WHERE idocorrencia=:idocorrencia";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $novoEstado);
        if (!empty($observacao)) {
            $stmt->bindParam(':observacao', $observacao);
        }
        $stmt->bindParam(':idocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0 || $atual['estado'] === $novoEstado) {
            $this->registarHistorico($idOcorrencia, $atual['estado'], $novoEstado, $_SESSION['idlogado'] ?? null, $observacao);
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Estado da ocorrência actualizado!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível actualizar o estado.</div>';
        return false;
    }

    public function encerrarOcorrencia(array $dados): bool {
        $dados['estado'] = 'Concluída';
        return $this->alterarEstado($dados);
    }

    public function deleteOcorrencia(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['idocorrencia']);
        $stmt = $this->conn->prepare("DELETE FROM ocorrencias WHERE idocorrencia=:idocorrencia");
        $stmt->bindParam(':idocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Ocorrência eliminada com sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar a ocorrência.</div>';
        return false;
    }

    private function registarHistorico(int $idOcorrencia, ?string $estadoAnterior, ?string $estadoNovo, $idUsuario, ?string $observacao): void {
        $estadoNovo = $estadoNovo ?? $this->dadosOcorrencia($idOcorrencia)['estado'] ?? 'Aberta';
        $stmt = $this->conn->prepare("
            INSERT INTO ocorrencia_historico (id_ocorrencia, estado_anterior, estado_novo, idusuario, observacao, created)
            VALUES (:id_ocorrencia, :estado_anterior, :estado_novo, :idusuario, :observacao, NOW())
        ");
        $stmt->bindParam(':id_ocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->bindParam(':estado_anterior', $estadoAnterior);
        $stmt->bindParam(':estado_novo', $estadoNovo);
        $stmt->bindParam(':idusuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->execute();
    }

    private function nifDoTecnico(?int $idUsuario): ?string {
        if (empty($idUsuario)) {
            return null;
        }
        $stmt = $this->conn->prepare("SELECT nif FROM usuario WHERE idusuario=:id LIMIT 1");
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['nif'] ?? null;
    }

    private function nomeDoTecnico(?int $idUsuario): ?string {
        if (empty($idUsuario)) {
            return null;
        }
        $stmt = $this->conn->prepare("SELECT nome, sobrenome FROM usuario WHERE idusuario=:id LIMIT 1");
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? trim($row['nome'] . ' ' . ($row['sobrenome'] ?? '')) : null;
    }
}
