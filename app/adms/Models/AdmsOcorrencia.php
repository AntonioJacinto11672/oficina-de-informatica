<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Gestão de Ocorrências — ponto de entrada do fluxo de manutenção:
 * Ocorrência → Diagnóstico → Execução da Manutenção → Conclusão → Histórico.
 */
class AdmsOcorrencia extends Conn {

    private $conn;

    public const ESTADOS = [
        'Aberta',
        'Em diagnóstico',
        'Aguardando execução',
        'Em execução',
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

    /**
     * Cria uma ocorrência 'Preventiva' a partir de um plano de manutenção
     * vencido (chamado por AdmsPlaneamento::gerarOcorrenciasPendentes()).
     */
    public function criarOcorrenciaPreventiva(int $idEquipamento, ?int $idTipoManutencao, ?int $idTecnico, ?string $observacao): int {
        $stmt = $this->conn->prepare("
            INSERT INTO ocorrencias (id_equipamento, id_tipo_manutencao, idtecnico_responsavel, categoria_manutencao, prioridade, descricao, estado, data_abertura, data_prevista, created)
            VALUES (:id_equipamento, :id_tipo_manutencao, :idtecnico_responsavel, 'Preventiva', 'Média', :descricao, 'Aberta', CURDATE(), CURDATE(), NOW())
        ");
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->bindParam(':id_tipo_manutencao', $idTipoManutencao, PDO::PARAM_INT);
        $stmt->bindParam(':idtecnico_responsavel', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':descricao', $observacao);
        $stmt->execute();

        $idOcorrencia = (int)$this->conn->lastInsertId();
        $this->vincularEquipamento($idOcorrencia, $idEquipamento);
        $this->registarHistorico($idOcorrencia, null, 'Aberta', null, 'Ocorrência preventiva gerada automaticamente pelo planeamento.');

        return $idOcorrencia;
    }

    private function vincularEquipamento(int $idOcorrencia, int $idEquipamento): void {
        $stmt = $this->conn->prepare("INSERT IGNORE INTO ocorrencia_equipamento (id_ocorrencia, id_equipamento) VALUES (:id_ocorrencia, :id_equipamento)");
        $stmt->bindParam(':id_ocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->bindParam(':id_equipamento', $idEquipamento, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function dadosOcorrencias(): array {
        $query = "
            SELECT
                o.idocorrencia, o.categoria_manutencao, o.prioridade, o.descricao, o.estado,
                o.data_abertura, o.data_prevista, o.data_encerramento, o.created,
                tm.nome AS tipo_manutencao,
                u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome,
                e.numero_serie, e.marca, e.modelo
            FROM ocorrencias o
            LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = o.id_tipo_manutencao
            LEFT JOIN usuario u ON u.idusuario = o.idtecnico_responsavel
            LEFT JOIN equipamento e ON e.idequipamento = o.id_equipamento
            ORDER BY o.created DESC
        ";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosHistoricoGeral(): array {
        $query = "
            SELECT
                o.idocorrencia, o.categoria_manutencao, o.prioridade, o.descricao, o.estado,
                o.data_abertura, o.data_prevista, o.data_encerramento, o.created,
                tm.nome AS tipo_manutencao,
                u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome,
                e.numero_serie, e.marca, e.modelo
            FROM ocorrencias o
            LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = o.id_tipo_manutencao
            LEFT JOIN usuario u ON u.idusuario = o.idtecnico_responsavel
            LEFT JOIN equipamento e ON e.idequipamento = o.id_equipamento
            WHERE o.estado IN ('Concluída', 'Cancelada')
            ORDER BY o.data_encerramento DESC, o.created DESC
        ";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosOcorrenciasDoTecnico(int $idTecnico): array {
        $stmt = $this->conn->prepare("
            SELECT o.*, tm.nome AS tipo_manutencao, e.numero_serie, e.marca, e.modelo
            FROM ocorrencias o
            LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = o.id_tipo_manutencao
            LEFT JOIN equipamento e ON e.idequipamento = o.id_equipamento
            WHERE o.idtecnico_responsavel = :id
            ORDER BY o.created DESC
        ");
        $stmt->bindParam(':id', $idTecnico, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosOcorrencia($idocorrencia) {
        $stmt = $this->conn->prepare("
            SELECT o.*, tm.nome AS tipo_manutencao
            FROM ocorrencias o
            LEFT JOIN tipo_manutencao tm ON tm.idtipo_manutencao = o.id_tipo_manutencao
            WHERE o.idocorrencia = :id LIMIT 1
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function dadosEquipamentosDaOcorrencia($idocorrencia): array {
        $stmt = $this->conn->prepare("
            SELECT e.idequipamento, e.numero_serie, e.marca, e.modelo, e.estado
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
            SELECT idequipamento, numero_serie, marca, modelo
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

    public function dadosTiposManutencao(): array {
        $stmt = $this->conn->prepare("SELECT idtipo_manutencao, nome, categoria FROM tipo_manutencao ORDER BY categoria, nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $idTipoManutencao = !empty($dados['id_tipo_manutencao']) ? (int)$dados['id_tipo_manutencao'] : null;
        $categoriaManutencao = in_array($dados['categoria_manutencao'] ?? '', ['Preventiva', 'Corretiva'], true) ? $dados['categoria_manutencao'] : 'Corretiva';
        $prioridade = in_array($dados['prioridade'] ?? '', self::PRIORIDADES, true) ? $dados['prioridade'] : 'Média';
        $descricao = !empty($dados['descricao']) ? $this->limparInput($dados['descricao']) : null;
        $dataPrevista = !empty($dados['data_prevista']) ? $this->limparInput($dados['data_prevista']) : null;
        $idTecnico = !empty($dados['idtecnico_responsavel']) ? (int)$dados['idtecnico_responsavel'] : null;
        $equipamentos = array_filter(array_map('intval', (array)($dados['equipamentos'] ?? [])));

        if (empty($equipamentos)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Selecione pelo menos um equipamento.</div>';
            return false;
        }

        $query = "INSERT INTO ocorrencias
                    (id_equipamento, id_tipo_manutencao, idtecnico_responsavel, categoria_manutencao, prioridade, descricao, estado, data_abertura, data_prevista, created)
                  VALUES
                    (:id_equipamento, :id_tipo_manutencao, :idtecnico_responsavel, :categoria_manutencao, :prioridade, :descricao, 'Aberta', CURDATE(), :data_prevista, NOW())";
        $equipamentos = array_values($equipamentos);
        $primeiroEquipamento = $equipamentos[0];
        $result = $this->conn->prepare($query);
        $result->bindParam(':id_equipamento', $primeiroEquipamento, PDO::PARAM_INT);
        $result->bindParam(':id_tipo_manutencao', $idTipoManutencao, PDO::PARAM_INT);
        $result->bindParam(':idtecnico_responsavel', $idTecnico, PDO::PARAM_INT);
        $result->bindParam(':categoria_manutencao', $categoriaManutencao);
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
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Ocorrência registada com sucesso.</div>';
            return true;
        }

        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editOcorrencia(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['idocorrencia']);
        $idTipoManutencao = !empty($dados['id_tipo_manutencao']) ? (int)$dados['id_tipo_manutencao'] : null;
        $categoriaManutencao = in_array($dados['categoria_manutencao'] ?? '', ['Preventiva', 'Corretiva'], true) ? $dados['categoria_manutencao'] : 'Corretiva';
        $prioridade = in_array($dados['prioridade'] ?? '', self::PRIORIDADES, true) ? $dados['prioridade'] : 'Média';
        $descricao = !empty($dados['descricao']) ? $this->limparInput($dados['descricao']) : null;
        $dataPrevista = !empty($dados['data_prevista']) ? $this->limparInput($dados['data_prevista']) : null;
        $equipamentos = array_filter(array_map('intval', (array)($dados['equipamentos'] ?? [])));

        $query = "UPDATE ocorrencias SET id_tipo_manutencao=:id_tipo_manutencao, categoria_manutencao=:categoria_manutencao,
                    prioridade=:prioridade, descricao=:descricao, data_prevista=:data_prevista
                  WHERE idocorrencia=:idocorrencia";
        $result = $this->conn->prepare($query);
        $result->bindParam(':id_tipo_manutencao', $idTipoManutencao, PDO::PARAM_INT);
        $result->bindParam(':categoria_manutencao', $categoriaManutencao);
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

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
        return true;
    }

    public function atribuirTecnico(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['idocorrencia']);
        $idTecnico = !empty($dados['idtecnico_responsavel']) ? (int)$dados['idtecnico_responsavel'] : null;

        $stmt = $this->conn->prepare("UPDATE ocorrencias SET idtecnico_responsavel=:idtecnico_responsavel WHERE idocorrencia=:idocorrencia");
        $stmt->bindParam(':idtecnico_responsavel', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':idocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $nomeTecnico = $this->nomeDoTecnico($idTecnico) ?? 'não atribuído';
            $this->registarHistorico($idOcorrencia, null, null, $_SESSION['idlogado'] ?? null, "Técnico responsável definido: {$nomeTecnico}.");
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Técnico atribuído com sucesso.</div>';
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
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Ocorrência não encontrada.</div>';
            return false;
        }

        $query = "UPDATE ocorrencias SET estado=:estado";
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
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Estado da ocorrência atualizado.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atualizar o estado.</div>';
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
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Ocorrência eliminada com sucesso.</div>';
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
