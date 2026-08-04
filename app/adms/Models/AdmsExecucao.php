<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Execução da Manutenção — sempre ligada a uma Ocorrência.
 */
class AdmsExecucao extends Conn {

    private $conn;

    public const ESTADOS = ['Em execução', 'Concluída', 'Cancelada'];

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    public function dadosExecucoes(): array {
        $stmt = $this->conn->prepare("
            SELECT ex.*, u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome
            FROM execucao_manutencao ex
            LEFT JOIN usuario u ON u.idusuario = ex.idusuario_tecnico
            ORDER BY ex.created DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosExecucoesDaOcorrencia($idocorrencia): array {
        $stmt = $this->conn->prepare("
            SELECT ex.*, u.nome AS tecnico_nome, u.sobrenome AS tecnico_sobrenome
            FROM execucao_manutencao ex
            LEFT JOIN usuario u ON u.idusuario = ex.idusuario_tecnico
            WHERE ex.id_ocorrencia = :id
            ORDER BY ex.created DESC
        ");
        $stmt->bindParam(':id', $idocorrencia, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dadosExecucao($idexecucao) {
        $stmt = $this->conn->prepare("SELECT * FROM execucao_manutencao WHERE idexecucao = :id LIMIT 1");
        $stmt->bindParam(':id', $idexecucao, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function dadosPecasDaExecucao($idexecucao): array {
        $stmt = $this->conn->prepare("
            SELECT ep.*, p.nome AS produto_nome
            FROM execucao_peca ep
            INNER JOIN produto p ON p.idproduto = ep.id_produto
            WHERE ep.id_execucao = :id
            ORDER BY ep.created
        ");
        $stmt->bindParam(':id', $idexecucao, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function iniciarExecucao(array $dados): bool {
        $idOcorrencia = (int)$this->limparInput($dados['id_ocorrencia']);
        $idTecnico = $_SESSION['idlogado'] ?? null;
        $observacoes = !empty($dados['observacoes']) ? $this->limparInput($dados['observacoes']) : null;

        $stmt = $this->conn->prepare("
            INSERT INTO execucao_manutencao (id_ocorrencia, idusuario_tecnico, data_inicio, estado, observacoes, created)
            VALUES (:id_ocorrencia, :idusuario_tecnico, NOW(), 'Em execução', :observacoes, NOW())
        ");
        $stmt->bindParam(':id_ocorrencia', $idOcorrencia, PDO::PARAM_INT);
        $stmt->bindParam(':idusuario_tecnico', $idTecnico, PDO::PARAM_INT);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $ocorrenciaModel = new AdmsOcorrencia();
            $ocorrenciaModel->alterarEstado([
                'idocorrencia' => $idOcorrencia,
                'estado' => 'Em execução',
                'observacao' => 'Execução da manutenção iniciada.',
            ]);
            foreach ($ocorrenciaModel->dadosEquipamentosDaOcorrencia($idOcorrencia) as $eq) {
                $upd = $this->conn->prepare("UPDATE equipamento SET estado='Em Manutenção' WHERE idequipamento=:id");
                $upd->bindParam(':id', $eq['idequipamento'], PDO::PARAM_INT);
                $upd->execute();
            }
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Execução iniciada com sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível iniciar a execução.</div>';
        return false;
    }

    public function addPeca(array $dados): bool {
        $idExecucao = (int)$this->limparInput($dados['id_execucao']);
        $idProduto = (int)$this->limparInput($dados['id_produto']);
        $quantidade = max(1, (int)$this->limparInput($dados['quantidade'] ?? 1));

        $stmt = $this->conn->prepare("SELECT estoque FROM produto WHERE idproduto = :id LIMIT 1");
        $stmt->bindParam(':id', $idProduto, PDO::PARAM_INT);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$produto || (int)$produto['estoque'] < $quantidade) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Estoque insuficiente para esta peça.</div>';
            return false;
        }

        $insert = $this->conn->prepare("INSERT INTO execucao_peca (id_execucao, id_produto, quantidade, created) VALUES (:id_execucao, :id_produto, :quantidade, NOW())");
        $insert->bindParam(':id_execucao', $idExecucao, PDO::PARAM_INT);
        $insert->bindParam(':id_produto', $idProduto, PDO::PARAM_INT);
        $insert->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $insert->execute();

        if ($insert->rowCount() > 0) {
            $novoEstoque = (int)$produto['estoque'] - $quantidade;
            $update = $this->conn->prepare("UPDATE produto SET estoque=:estoque WHERE idproduto=:id");
            $update->bindParam(':estoque', $novoEstoque, PDO::PARAM_INT);
            $update->bindParam(':id', $idProduto, PDO::PARAM_INT);
            $update->execute();
            (new AdmsMovimentoEstoque())->registar($idProduto, 'Saida', $quantidade, 'Execucao', $idExecucao, $_SESSION['idlogado'] ?? null);
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Peça adicionada à execução!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível adicionar a peça.</div>';
        return false;
    }

    public function removerPeca(array $dados): bool {
        $idExecucaoPeca = (int)$this->limparInput($dados['idexecucao_peca']);

        $stmt = $this->conn->prepare("SELECT id_produto, quantidade FROM execucao_peca WHERE idexecucao_peca = :id LIMIT 1");
        $stmt->bindParam(':id', $idExecucaoPeca, PDO::PARAM_INT);
        $stmt->execute();
        $peca = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$peca) {
            return false;
        }

        $del = $this->conn->prepare("DELETE FROM execucao_peca WHERE idexecucao_peca = :id");
        $del->bindParam(':id', $idExecucaoPeca, PDO::PARAM_INT);
        $del->execute();

        if ($del->rowCount() > 0) {
            $update = $this->conn->prepare("UPDATE produto SET estoque = estoque + :quantidade WHERE idproduto = :id");
            $update->bindParam(':quantidade', $peca['quantidade'], PDO::PARAM_INT);
            $update->bindParam(':id', $peca['id_produto'], PDO::PARAM_INT);
            $update->execute();
            (new AdmsMovimentoEstoque())->registar((int)$peca['id_produto'], 'Entrada', (int)$peca['quantidade'], 'ExecucaoRemoverPeca', $idExecucaoPeca, $_SESSION['idlogado'] ?? null);
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Peça removida e estoque restituído!</div>';
            return true;
        }
        return false;
    }

    public function encerrarExecucao(array $dados): bool {
        $idExecucao = (int)$this->limparInput($dados['idexecucao']);
        $execucao = $this->dadosExecucao($idExecucao);
        if (!$execucao) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE execucao_manutencao SET estado='Concluída', data_fim=NOW() WHERE idexecucao=:id");
        $stmt->bindParam(':id', $idExecucao, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $ocorrenciaModel = new AdmsOcorrencia();
            $ocorrenciaModel->encerrarOcorrencia([
                'idocorrencia' => $execucao['id_ocorrencia'],
                'observacao' => 'Execução da manutenção concluída.',
            ]);

            // Actualizar estado dos equipamentos associados (passo 10 do fluxo geral)
            $equipamentos = $ocorrenciaModel->dadosEquipamentosDaOcorrencia($execucao['id_ocorrencia']);
            foreach ($equipamentos as $eq) {
                $upd = $this->conn->prepare("UPDATE equipamento SET estado='Disponível' WHERE idequipamento=:id");
                $upd->bindParam(':id', $eq['idequipamento'], PDO::PARAM_INT);
                $upd->execute();
            }

            $_SESSION['msg'] = '<div class="alert alert-success text-center">Execução encerrada com sucesso!</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível encerrar a execução.</div>';
        return false;
    }
}
