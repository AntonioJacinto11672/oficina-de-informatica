<?php

namespace App\adms\Models;

use PDO;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Estatísticas de manutenção — ocorrências por mês, preventiva vs corretiva,
 * equipamentos por estado e peças mais utilizadas. Sem indicadores financeiros.
 */
class AdmsGraficos extends Conn {

    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    public function dadosOcorrenciasPorMes(): array {
        $ano = date('Y');
        $stmt = $this->conn->prepare("
            SELECT MONTH(created) AS mes, COUNT(*) AS total
            FROM ocorrencias
            WHERE YEAR(created) = :ano
            GROUP BY MONTH(created)
        ");
        $stmt->bindParam(':ano', $ano);
        $stmt->execute();
        $porMes = array_fill(1, 12, 0);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $porMes[(int)$row['mes']] = (int)$row['total'];
        }
        return array_values($porMes);
    }

    public function dadosPreventivaVsCorretiva(): array {
        $stmt = $this->conn->prepare("SELECT categoria_manutencao, COUNT(*) AS total FROM ocorrencias GROUP BY categoria_manutencao");
        $stmt->execute();
        $dados = ['Preventiva' => 0, 'Corretiva' => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $dados[$row['categoria_manutencao']] = (int)$row['total'];
        }
        return $dados;
    }

    public function dadosEquipamentosPorEstado(): array {
        $stmt = $this->conn->prepare("SELECT estado, COUNT(*) AS total FROM equipamento GROUP BY estado");
        $stmt->execute();
        $dados = ['Disponível' => 0, 'Em Manutenção' => 0, 'Avariado' => 0, 'Abatido' => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $dados[$row['estado']] = (int)$row['total'];
        }
        return $dados;
    }

    public function dadosPecasMaisUtilizadas(): array {
        $stmt = $this->conn->prepare("
            SELECT p.nome, SUM(ep.quantidade) AS total
            FROM execucao_peca ep
            INNER JOIN produto p ON p.idproduto = ep.id_produto
            GROUP BY ep.id_produto
            ORDER BY total DESC
            LIMIT 10
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
