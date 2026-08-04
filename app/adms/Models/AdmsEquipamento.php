<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Equipamentos Informáticos da Universidade Lusíada de Angola.
 */
class AdmsEquipamento extends Conn {

    private $conn;

    public const ESTADOS = ['Disponível', 'Em Manutenção', 'Avariado', 'Abatido'];

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosEquipamentos(): array {
        $stmt = $this->conn->prepare("SELECT * FROM dadosEquipamento ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosEquipamento($id) {
        $stmt = $this->conn->prepare("SELECT * FROM dadosEquipamento WHERE idequipamento = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function dadosDepartamentos(): array {
        $stmt = $this->conn->prepare("SELECT iddepartamento, nome FROM departamentos ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosCategorias(): array {
        $stmt = $this->conn->prepare("SELECT idcategoria_equipamento, nome FROM categoria_equipamento ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosFornecedores(): array {
        $stmt = $this->conn->prepare("SELECT idfornecedor, nome FROM fornecedor ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosResponsaveis(): array {
        $stmt = $this->conn->prepare("SELECT idusuario, nome, sobrenome FROM usuario WHERE st_conta = 'Ativada' ORDER BY nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Histórico de manutenção do equipamento: ocorrências, diagnósticos e
     * execuções ligados a ele, ordenados cronologicamente.
     */
    public function dadosHistorico($idEquipamento): array {
        $stmt = $this->conn->prepare("
            SELECT o.idocorrencia, o.categoria_manutencao, o.prioridade, o.estado, o.descricao,
                   o.data_abertura, o.data_encerramento, o.created,
                   u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome
            FROM ocorrencias o
            LEFT JOIN ocorrencia_equipamento oe ON oe.id_ocorrencia = o.idocorrencia
            LEFT JOIN usuario u ON u.idusuario = o.idtecnico_responsavel
            WHERE o.id_equipamento = :id OR oe.id_equipamento = :id2
            GROUP BY o.idocorrencia
            ORDER BY o.created DESC
        ");
        $stmt->bindParam(':id', $idEquipamento, PDO::PARAM_INT);
        $stmt->bindParam(':id2', $idEquipamento, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsEquipamento(array $dados): bool {
        $numeroSerie = $this->limparInput($dados['numero_serie'] ?? '');
        $nome = $this->limparInput($dados['nome'] ?? '');

        if ($numeroSerie === '' || $nome === '') {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Preencha os campos obrigatórios.</div>';
            return false;
        }
        if (!$this->numeroSerieDisponivel($numeroSerie)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um equipamento registado com este número de série.</div>';
            return false;
        }

        $codigo = $this->limparInput($dados['codigo'] ?? '') ?: null;
        $patrimonio = $this->limparInput($dados['patrimonio'] ?? '') ?: null;
        $iddepartamento = !empty($dados['iddepartamento']) ? (int)$dados['iddepartamento'] : null;
        $idresponsavel = !empty($dados['idresponsavel']) ? (int)$dados['idresponsavel'] : null;
        $localizacao = $this->limparInput($dados['localizacao'] ?? '') ?: null;
        $idcategoria = !empty($dados['idcategoria_equipamento']) ? (int)$dados['idcategoria_equipamento'] : null;
        $marca = $this->limparInput($dados['marca'] ?? '') ?: null;
        $modelo = $this->limparInput($dados['modelo'] ?? '') ?: null;
        $estado = in_array($dados['estado'] ?? '', self::ESTADOS, true) ? $dados['estado'] : 'Disponível';
        $idfornecedor = !empty($dados['idfornecedor']) ? (int)$dados['idfornecedor'] : null;
        $dataAquisicao = !empty($dados['data_aquisicao']) ? $this->limparInput($dados['data_aquisicao']) : null;
        $garantiaAte = !empty($dados['garantia_ate']) ? $this->limparInput($dados['garantia_ate']) : null;
        $observacoes = $this->limparInput($dados['observacoes'] ?? '') ?: null;

        $stmt = $this->conn->prepare("
            INSERT INTO equipamento (codigo, patrimonio, nome, iddepartamento, idresponsavel, localizacao, numero_serie,
                                      idcategoria_equipamento, marca, modelo, estado, idfornecedor, data_aquisicao, garantia_ate,
                                      observacoes, dataregisto, created)
            VALUES (:codigo, :patrimonio, :nome, :iddepartamento, :idresponsavel, :localizacao, :numero_serie,
                    :idcategoria, :marca, :modelo, :estado, :idfornecedor, :data_aquisicao, :garantia_ate,
                    :observacoes, CURDATE(), NOW())
        ");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':patrimonio', $patrimonio);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':iddepartamento', $iddepartamento, PDO::PARAM_INT);
        $stmt->bindParam(':idresponsavel', $idresponsavel, PDO::PARAM_INT);
        $stmt->bindParam(':localizacao', $localizacao);
        $stmt->bindParam(':numero_serie', $numeroSerie);
        $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idfornecedor', $idfornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':data_aquisicao', $dataAquisicao);
        $stmt->bindParam(':garantia_ate', $garantiaAte);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Equipamento registado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function editEquipamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['idequipamento']);
        $numeroSerie = $this->limparInput($dados['numero_serie'] ?? '');
        $nome = $this->limparInput($dados['nome'] ?? '');

        if (!$this->numeroSerieDisponivel($numeroSerie, $id)) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um equipamento registado com este número de série.</div>';
            return false;
        }

        $codigo = $this->limparInput($dados['codigo'] ?? '') ?: null;
        $patrimonio = $this->limparInput($dados['patrimonio'] ?? '') ?: null;
        $iddepartamento = !empty($dados['iddepartamento']) ? (int)$dados['iddepartamento'] : null;
        $idresponsavel = !empty($dados['idresponsavel']) ? (int)$dados['idresponsavel'] : null;
        $localizacao = $this->limparInput($dados['localizacao'] ?? '') ?: null;
        $idcategoria = !empty($dados['idcategoria_equipamento']) ? (int)$dados['idcategoria_equipamento'] : null;
        $marca = $this->limparInput($dados['marca'] ?? '') ?: null;
        $modelo = $this->limparInput($dados['modelo'] ?? '') ?: null;
        $estado = in_array($dados['estado'] ?? '', self::ESTADOS, true) ? $dados['estado'] : 'Disponível';
        $idfornecedor = !empty($dados['idfornecedor']) ? (int)$dados['idfornecedor'] : null;
        $dataAquisicao = !empty($dados['data_aquisicao']) ? $this->limparInput($dados['data_aquisicao']) : null;
        $garantiaAte = !empty($dados['garantia_ate']) ? $this->limparInput($dados['garantia_ate']) : null;
        $observacoes = $this->limparInput($dados['observacoes'] ?? '') ?: null;

        $stmt = $this->conn->prepare("
            UPDATE equipamento SET codigo=:codigo, patrimonio=:patrimonio, nome=:nome, iddepartamento=:iddepartamento,
                idresponsavel=:idresponsavel, localizacao=:localizacao, numero_serie=:numero_serie,
                idcategoria_equipamento=:idcategoria, marca=:marca, modelo=:modelo, estado=:estado,
                idfornecedor=:idfornecedor, data_aquisicao=:data_aquisicao, garantia_ate=:garantia_ate, observacoes=:observacoes
            WHERE idequipamento=:id
        ");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':patrimonio', $patrimonio);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':iddepartamento', $iddepartamento, PDO::PARAM_INT);
        $stmt->bindParam(':idresponsavel', $idresponsavel, PDO::PARAM_INT);
        $stmt->bindParam(':localizacao', $localizacao);
        $stmt->bindParam(':numero_serie', $numeroSerie);
        $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idfornecedor', $idfornecedor, PDO::PARAM_INT);
        $stmt->bindParam(':data_aquisicao', $dataAquisicao);
        $stmt->bindParam(':garantia_ate', $garantiaAte);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
        return false;
    }

    public function deleteEquipamento(array $dados): bool {
        $id = (int)$this->limparInput($dados['idequipamento']);
        $stmt = $this->conn->prepare("DELETE FROM equipamento WHERE idequipamento=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Equipamento eliminado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar o equipamento. Verifique se existem ocorrências associadas.</div>';
        return false;
    }

    private function numeroSerieDisponivel(string $numeroSerie, ?int $idIgnorar = null): bool {
        if ($idIgnorar) {
            $stmt = $this->conn->prepare("SELECT idequipamento FROM equipamento WHERE numero_serie = :ns AND idequipamento <> :id LIMIT 1");
            $stmt->bindParam(':id', $idIgnorar, PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare("SELECT idequipamento FROM equipamento WHERE numero_serie = :ns LIMIT 1");
        }
        $stmt->bindParam(':ns', $numeroSerie);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }
}
