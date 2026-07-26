<?php

namespace App\adms\Models;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


/**
 * Description of AdmsHome
 *
 * @author Double
 */
class AdmsHome extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    public function index() {
        $mes = date("m");
        $ano = date("Y");
        $this->dados['data_inicialMes'] = $ano . "-" . $mes . "-01";
        $this->dados['ocorrencias_preventivas'] = $this->dadosOcorrenciasPreventivas();
        if ($_SESSION['usuario'] == "adimin" || $_SESSION['usuario'] == "recep") {
            $this->dadosOperacaoMovimentacaoHome();
            $this->dadosHomeAdm();
            return $this->dados;
        } elseif ($_SESSION['usuario'] == "tecnico") {
            $this->dadosHomeMec();
            return $this->dados;
        } else {
            return null;
        }
    }

    public function filterROrcamento($param) {
        $this->dados = $param;

        if ($this->dados['status'] == "Todos") {
            $result = $this->conn->prepare("SELECT * FROM dadosorcamento WHERE tipo = '{$this->dados['tipo']}' AND data_orcamento  BETWEEN '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'");
            $result->execute();
            $this->dados = $result->fetchAll();
            return $this->dados;
        } else {
            $result = $this->conn->prepare("SELECT * FROM dadosorcamento WHERE status = '{$this->dados['status']}' AND tipo = '{$this->dados['tipo']}' AND data_orcamento  BETWEEN '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'");
            $result->execute();
            $this->dados = $result->fetchAll();
            return $this->dados;
        }
        //var_dump($this->dados);
    }

    public function dadosMovimentacao($dados) {
        $this->dados = $dados;
        if ($this->dados['status'] == "Todos") {
            $query_dados = "SELECT * FROM movimentacao WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'";
            $result_dados = $this->conn->prepare($query_dados);
            $result_dados->execute();
            $this->dados = $result_dados->fetchAll();
            return $this->dados;
        } else {
            $query_dados = "SELECT * FROM movimentacao WHERE  tipo = '{$this->dados['status']}' AND data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}' ";
            $result_dados = $this->conn->prepare($query_dados);
            $result_dados->execute();
            $this->dados = $result_dados->fetchAll();
            return $this->dados;
        }
    }

    public function dadosOperacaoMovimentacao($dados) {
        //Valor Entrada do Dia
        $this->dados = $dados;
        if ($this->dados['status'] == "Todos") {
            $result_entrada = $this->conn->prepare("SELECT SUM(valor) as entrada_dia FROM movimentacao WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}' AND tipo='Entrada'");
            $result_entrada->execute();
            $resultado_entrada = $result_entrada->fetch(PDO::FETCH_ASSOC);
            $this->dados['entrada_dia'] = $resultado_entrada['entrada_dia'];
            //Saida do Dia
            $result_saida = $this->conn->prepare("SELECT SUM(valor) as saida_dia FROM movimentacao WHERE  data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}' AND tipo='Saída'");
            $result_saida->execute();
            $resultado_saida = $result_saida->fetch(PDO::FETCH_ASSOC);
            $this->dados['saida_dia'] = $resultado_saida['saida_dia'];

            //total do Dia
            /* $result_total = $this->conn->prepare("SELECT SUM(valor) as total FROM movimentacao WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'");
              $result_total->execute();
              $resultado_total = $result_total->fetch(PDO::FETCH_ASSOC); */
            @$this->dados['total'] = @$this->dados['entrada_dia'] - @$this->dados['saida_dia'];

            return $this->dados;
        } elseif ($this->dados['status'] == "Entrada") {
            $result_entrada = $this->conn->prepare("SELECT SUM(valor) as entrada_dia FROM movimentacao WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}' AND tipo='Entrada'");
            $result_entrada->execute();
            $resultado_entrada = $result_entrada->fetch(PDO::FETCH_ASSOC);
            $this->dados['entrada_dia'] = $resultado_entrada['entrada_dia'];

            $this->dados['saida_dia'] = 0;
            $this->dados['total'] = $this->dados['entrada_dia'];
            return $this->dados;
        } elseif ($this->dados['status'] == "Saída") {
            //Saida do Dia
            $result_saida = $this->conn->prepare("SELECT SUM(valor) as saida_dia FROM movimentacao WHERE  data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}' AND tipo='Saída'");
            $result_saida->execute();
            $resultado_saida = $result_saida->fetch(PDO::FETCH_ASSOC);
            $this->dados['saida_dia'] = $resultado_saida['saida_dia'];
            $this->dados['entrada_dia'] = 0;
            $this->dados['total'] = -($this->dados['saida_dia']);
            return $this->dados;
        }
    }

    //Contas À Pagar
    public function dadoscontaPagar($dados) {
        $this->dados = $dados;
        $query = "SELECT * FROM contas_apagar WHERE data_venci between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}' ORDER BY pago ASC, data_venci ASC";
        $result = $this->conn->prepare($query);
        $result->execute();
        $this->dados = $result->fetchAll();
        return $this->dados;
    }

    public function dadosContaReceber($dados) {
        $this->dados = $dados;
        $query_dados = "SELECT * FROM conntas_areceber WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function dadosCompras($dados) {
        $this->dados = $dados;
        $query_dadosCompras = "SELECT * FROM compras WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'";
        $result_dadosComras = $this->conn->prepare($query_dadosCompras);
        $result_dadosComras->execute();
        $this->dados = $result_dadosComras->fetchAll();
        return $this->dados;
    }

    public function dadosVendas($dados) {
        $this->dados = $dados;
        $query_dadosVendas = "SELECT * FROM vendas WHERE data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'";
        $result_dadosComras = $this->conn->prepare($query_dadosVendas);
        $result_dadosComras->execute();
        $this->dados = $result_dadosComras->fetchAll();
        return $this->dados;
    }

    // Dados Para O HOME
    private function dadosOperacaoMovimentacaoHome() {
        //Valor Entrada do Dia
        $mes = date("m");
        $ano = date("Y");
        $this->dados['data_inicialMes'] = $ano . "-" . $mes . "-01";
        $result_entrada = $this->conn->prepare("SELECT SUM(valor) as entrada_dia FROM movimentacao WHERE tipo='Entrada' AND data=curDate()");
        $result_entrada->execute();
        $resultado_entrada = $result_entrada->fetch(PDO::FETCH_ASSOC);
        $this->dados['entrada_dia'] = $resultado_entrada['entrada_dia'];
        //Saida do Dia
        $result_saida = $this->conn->prepare("SELECT SUM(valor) as saida_dia FROM movimentacao WHERE tipo='Saída' AND data=curDate()");
        $result_saida->execute();
        $resultado_saida = $result_saida->fetch(PDO::FETCH_ASSOC);
        $this->dados['saida_dia'] = $resultado_saida['saida_dia'];


        @$this->dados['saldo_dia'] = @$this->dados['entrada_dia'] - @$this->dados['saida_dia'];

        //Valor Entrada do Mês
        $result_entrada = $this->conn->prepare("SELECT SUM(valor) as entrada_mes FROM movimentacao WHERE tipo='Entrada' AND data between '{$this->dados['data_inicialMes']}' AND curDate()");
        $result_entrada->execute();
        $resultado_entrada = $result_entrada->fetch(PDO::FETCH_ASSOC);
        $this->dados['entrada_mes'] = $resultado_entrada['entrada_mes'];
        //var_dump($this->dados);
        //Saida do Mês
        $result_saida = $this->conn->prepare("SELECT SUM(valor) as saida_mes FROM movimentacao WHERE tipo='Saída' AND data between '{$this->dados['data_inicialMes']}' AND curDate()");
        $result_saida->execute();
        $resultado_saida = $result_saida->fetch(PDO::FETCH_ASSOC);
        $this->dados['saida_mes'] = $resultado_saida['saida_mes'];

        //Saldo do Mês
        @$this->dados['saldo_mes'] = @$this->dados['entrada_mes'] - @$this->dados['saida_mes'];

        //return $this->dados;
    }

    // Dados Comissão
    public function dadosComissao($dados) {
        $this->dados = $dados;
        $query_dados = "SELECT * FROM comissao WHERE niftecnico ='{$_SESSION['nif']}' AND data between '{$this->dados['data_inicio']}' AND '{$this->dados['data_final']}'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }

    public function dadosOcorrenciasPreventivas() {
        // Ocorrências preventivas já abertas + planos (módulo 13) ainda por gerar.
        // Se o módulo/ tabela `plano_manutencao_preventiva` não existir, retornamos
        // apenas as ocorrências para evitar erro fatal na dashboard.
        $query = "
            SELECT o.data_prevista, e.numero_serie, e.marca, e.modelo, ts.nome AS tipo_servico, o.tipo_manutencao, 'ocorrencia' AS origem
                  FROM ocorrencias o
                  LEFT JOIN equipamento e ON e.idequipamento = o.id_equipamento
                  LEFT JOIN tipo_servico ts ON ts.idtipo_servico = o.id_tipo_servico
                  WHERE o.tipo_manutencao = 'Preventiva'
                    AND o.status NOT IN ('Concluída', 'Encerrada')
                    AND o.data_prevista BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";

        try {
            $tableExists = $this->conn->query("SHOW TABLES LIKE 'plano_manutencao_preventiva'")->fetchColumn();
            if ($tableExists) {
                $query .= "
            UNION ALL
            SELECT p.proxima_execucao AS data_prevista, e.numero_serie, e.marca, e.modelo, ts.nome AS tipo_servico, 'Preventiva (planeada)' AS tipo_manutencao, 'plano' AS origem
                  FROM plano_manutencao_preventiva p
                  INNER JOIN equipamento e ON e.idequipamento = p.id_equipamento
                  LEFT JOIN tipo_servico ts ON ts.idtipo_servico = p.id_tipo_servico
                  WHERE p.ativo = 1
                    AND p.proxima_execucao BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
            }
            $query .= "\n            ORDER BY data_prevista ASC";

            $result = $this->conn->prepare($query);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function dadosHomeAdm() {
        //Orçamentoss Concluídos
        $mes = date("m");
        $ano = date("Y");
        $this->dados['data_inicialMes'] = $ano . "-" . $mes . "-01";
        $result = $this->conn->prepare("SELECT count(idorcamentos) as orc_concluidos FROM dadosorcamento WHERE status = 'concluído' AND data_orcamento >= '{$this->dados['data_inicialMes']}' AND data_orcamento <= curDate()");
        $result->execute();
        $orcamentos = $result->fetch();
        $this->dados['orc_concluidos'] = $orcamentos['orc_concluidos'];

        //Orçamentos Pendentes
        $result = $this->conn->prepare("SELECT count(idorcamentos) as orc_pendentes FROM dadosorcamento WHERE status = 'Aberto' AND tipo ='orcamento' AND data_orcamento >= '{$this->dados['data_inicialMes']}' AND data_orcamento <= curDate()");
        $result->execute();
        $orcamentos = $result->fetch();
        $this->dados['orc_pendentes'] = $orcamentos['orc_pendentes'];

        $result = $this->conn->prepare("SELECT count(idorcamentos) as orc_aprovados FROM dadosorcamento WHERE status = 'Aprovado' AND data_orcamento >= '{$this->dados['data_inicialMes']}' AND data_orcamento <= curDate()");
        $result->execute();
        $orcamentos = $result->fetch();
        $this->dados['orc_aprovados'] = $orcamentos['orc_aprovados'];
        //Dados erviços Pendetes

        $result = $this->conn->prepare("SELECT count(idorcamentos) as serv_pendentes FROM dadosorcamento WHERE status = 'Aberto' AND tipo ='servico' AND data_orcamento >= '{$this->dados['data_inicialMes']}' AND data_orcamento <= curDate()");
        $result->execute();
        $orcamentos = $result->fetch();
        $this->dados['serv_pendentes'] = $orcamentos['serv_pendentes'];

        // Dados Do Produtos
        $result = $this->conn->prepare("SELECT count(idproduto) as produto FROM produto");
        $result->execute();
        $produtos = $result->fetch();
        $this->dados['produto'] = $produtos['produto'];

        // Recepcionista
        $result = $this->conn->prepare("SELECT count(idrecepcionista) as recepcionista FROM recepcionista");
        $result->execute();
        $recepcionista = $result->fetch();
        $this->dados['recepcionista'] = $recepcionista['recepcionista'];

        //Mecanicos
        $result = $this->conn->prepare("SELECT count(idtecnico) as mecanicos FROM tecnicos");
        $result->execute();
        $mecanicos = $result->fetch();
        $this->dados['mecanicos'] = $mecanicos['mecanicos'];

        //Clientes
        $result = $this->conn->prepare("SELECT count(idclientes) as clientes FROM clientes");
        $result->execute();
        $clientes = $result->fetch();
        $this->dados['clientes'] = $clientes['clientes'];

        //Equipamentos
        $result = $this->conn->prepare("SELECT count(idequipamento) as equipamento FROM equipamento");
        $result->execute();
        $equipamento = $result->fetch();
        $this->dados['veiculo'] = $equipamento['equipamento'];


        //var_dump($this->dados);
    }

    private function dadosHomeMec() {
        //Orçamentoss Concluídos
        $mes = date("m");
        $ano = date("Y");
        $this->dados['data_inicialMes'] = $ano . "-" . $mes . "-01";
        $result = $this->conn->prepare("SELECT count(idorcamentos) as orc_concluidos FROM dadosorcamento WHERE status = 'concluído' AND mecanico = '{$_SESSION['nif']}' AND data_orcamento >= '{$this->dados['data_inicialMes']}' AND data_orcamento <= curDate()");
        $result->execute();
        $orcamentos = $result->fetch();
        $this->dados['orc_concluidos'] = $orcamentos['orc_concluidos'];

        $result = $this->conn->prepare("SELECT count(idorcamentos) as serv_pendentes FROM dadosorcamento WHERE status = 'Aberto' AND mecanico = '{$_SESSION['nif']}' AND data_orcamento >= '{$this->dados['data_inicialMes']}' AND data_orcamento <= curDate()");
        $result->execute();
        $orcamentos = $result->fetch();
        $this->dados['serv_pendentes'] = $orcamentos['serv_pendentes'];

        //Comissões
        //Comssão do Dia
        $result = $this->conn->prepare("SELECT SUM(valor) as comissao_hoje FROM comissao WHERE niftecnico = '{$_SESSION['nif']}' AND data = curDate()");
        $result->execute();
        $comissao = $result->fetch();
        $this->dados['comissao_hoje'] = $comissao['comissao_hoje'];

        //Comssão do Mês
        $result = $this->conn->prepare("SELECT SUM(valor) as comissao_mes FROM comissao WHERE niftecnico = '{$_SESSION['nif']}' AND data >= '{$this->dados['data_inicialMes']}' AND data <= curDate()");
        $result->execute();
        $comissao = $result->fetch();
        $this->dados['comissao_mes'] = $comissao['comissao_mes'];
        // Carros Que Estão em Atrasos Ou A data De Eb«ntrega É Hoje
        $result = $this->conn->prepare("SELECT * FROM dadosorcamento WHERE mecanico = '{$_SESSION['nif']}' AND status = 'Aprovado' AND tipo = 'Orcamento' AND data_entrega <= curDate()");
        $result->execute();
        $comissao = $result->fetchAll(PDO::FETCH_ASSOC);
        $this->dados['veiculos_atrasado'] = $comissao;

        // Carros ue Tem a Sua Datas De Entregas Distantes
        $result = $this->conn->prepare("SELECT * FROM dadosorcamento WHERE mecanico = '{$_SESSION['nif']}' AND status = 'Aprovado' AND tipo = 'Orcamento' AND  data_entrega > curDate()");
        $result->execute();
        $comissao = $result->fetchAll(PDO::FETCH_ASSOC);
        $this->dados['veiculos_adiantados'] = $comissao;


        //var_dump($this->dados['veiculos_atrasado']);
    }
    // Relatório Pela  Mecanica
    public function dadosOrcamentosCompletoAlter($dados) {
        $this->dados = $dados;
        $query_dados = "SELECT * FROM dadosOrcamentosCompletoComProdutos WHERE idorcamentos='{$this->dados['idorcamentos']}'";
        $result_dados = $this->conn->prepare($query_dados);
        $result_dados->execute();
        $this->dados = $result_dados->fetchAll();
        return $this->dados;
    }
    
    public function dadosGrafico() {
        $mes = date("m");
        $ano = date("Y");
        //Movimentação de Janeiro
        $this->dados['inicialMes'] = $ano . "-" ."01"."-01";
        $this->dados['finalMes'] = $ano . "-" ."01"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as janeiro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['janeiro'] = $resultado['janeiro'];
        
        
        //Movimentação de fevereiro
        $this->dados['inicialMes'] = $ano . "-" ."02"."-01";
        $this->dados['finalMes'] = $ano . "-" ."02"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as fevereiro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['fevereiro'] = $resultado['fevereiro'];
        
        
        //Movimentação de Março
        $this->dados['inicialMes'] = $ano . "-" ."03"."-01";
        $this->dados['finalMes'] = $ano . "-" ."03"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as marco FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['marco'] = $resultado['marco'];
        
        
        //Movimentação de Abril
        $this->dados['inicialMes'] = $ano . "-" ."04"."-01";
        $this->dados['finalMes'] = $ano . "-" ."04"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as abril FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['abril'] = $resultado['abril'];
        
        
        //Movimentação de Maio
        $this->dados['inicialMes'] = $ano . "-" ."05"."-01";
        $this->dados['finalMes'] = $ano . "-" ."05"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as maio FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['maio'] = $resultado['maio'];
        
        
        //Movimentação de Junho
        $this->dados['inicialMes'] = $ano . "-" ."06"."-01";
        $this->dados['finalMes'] = $ano . "-" ."06"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as junho FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['junho'] = $resultado['junho'];
        
        
        //Movimentação de Julho
        $this->dados['inicialMes'] = $ano . "-" ."07"."-01";
        $this->dados['finalMes'] = $ano . "-" ."07"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as julho FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['julho'] = $resultado['julho'];
        
        //Movimentação de Agosto
        $this->dados['inicialMes'] = $ano . "-" ."08"."-01";
        $this->dados['finalMes'] = $ano . "-" ."08"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as agosto FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['agosto'] = $resultado['agosto'];
        
        //Movimentação de Setembro
        $this->dados['inicialMes'] = $ano . "-" ."09"."-01";
        $this->dados['finalMes'] = $ano . "-" ."09"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as setembro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['setembro'] = $resultado['setembro'];
        
        //Movimentação de Outobro
        $this->dados['inicialMes'] = $ano . "-" ."10"."-01";
        $this->dados['finalMes'] = $ano . "-" ."10"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as outobro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['outobro'] = $resultado['outobro'];
        
        //Movimentação de Novembro
        $this->dados['inicialMes'] = $ano . "-" ."11"."-01";
        $this->dados['finalMes'] = $ano . "-" ."11"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as novembro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['novembro'] = $resultado['novembro'];
        
        //Movimentação de Dezembro
        $this->dados['inicialMes'] = $ano . "-" ."12"."-01";
        $this->dados['finalMes'] = $ano . "-" ."12"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as dezembro FROM movimentacao WHERE data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['dezembro'] = $resultado['dezembro'];
        
        //var_dump($resultado);
        return $this->dados;
    }
    
    public function dadosGraficoMoviEntrada() {
        $mes = date("m");
        $ano = date("Y");
        //Movimentação de Janeiro
        $this->dados['inicialMes'] = $ano . "-" ."01"."-01";
        $this->dados['finalMes'] = $ano . "-" ."01"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as janeiro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['janeiro'] = $resultado['janeiro'];
        
        
        //Movimentação de fevereiro
        $this->dados['inicialMes'] = $ano . "-" ."02"."-01";
        $this->dados['finalMes'] = $ano . "-" ."02"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as fevereiro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['fevereiro'] = $resultado['fevereiro'];
        
        
        //Movimentação de Março
        $this->dados['inicialMes'] = $ano . "-" ."03"."-01";
        $this->dados['finalMes'] = $ano . "-" ."03"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as marco FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['marco'] = $resultado['marco'];
        
        
        //Movimentação de Abril
        $this->dados['inicialMes'] = $ano . "-" ."04"."-01";
        $this->dados['finalMes'] = $ano . "-" ."04"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as abril FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['abril'] = $resultado['abril'];
        
        
        //Movimentação de Maio
        $this->dados['inicialMes'] = $ano . "-" ."05"."-01";
        $this->dados['finalMes'] = $ano . "-" ."05"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as maio FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['maio'] = $resultado['maio'];
        
        
        //Movimentação de Junho
        $this->dados['inicialMes'] = $ano . "-" ."06"."-01";
        $this->dados['finalMes'] = $ano . "-" ."06"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as junho FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['junho'] = $resultado['junho'];
        
        
        //Movimentação de Julho
        $this->dados['inicialMes'] = $ano . "-" ."07"."-01";
        $this->dados['finalMes'] = $ano . "-" ."07"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as julho FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['julho'] = $resultado['julho'];
        
        //Movimentação de Agosto
        $this->dados['inicialMes'] = $ano . "-" ."08"."-01";
        $this->dados['finalMes'] = $ano . "-" ."08"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as agosto FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['agosto'] = $resultado['agosto'];
        
        //Movimentação de Setembro
        $this->dados['inicialMes'] = $ano . "-" ."09"."-01";
        $this->dados['finalMes'] = $ano . "-" ."09"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as setembro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['setembro'] = $resultado['setembro'];
        
        //Movimentação de Outobro
        $this->dados['inicialMes'] = $ano . "-" ."10"."-01";
        $this->dados['finalMes'] = $ano . "-" ."10"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as outobro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['outobro'] = $resultado['outobro'];
        
        //Movimentação de Novembro
        $this->dados['inicialMes'] = $ano . "-" ."11"."-01";
        $this->dados['finalMes'] = $ano . "-" ."11"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as novembro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['novembro'] = $resultado['novembro'];
        
        //Movimentação de Dezembro
        $this->dados['inicialMes'] = $ano . "-" ."12"."-01";
        $this->dados['finalMes'] = $ano . "-" ."12"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as dezembro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['dezembro'] = $resultado['dezembro'];
        
        //var_dump($resultado);
        return $this->dados;
    }
    
    public function dadosGraficoMoviSaida() {
        $mes = date("m");
        $ano = date("Y");
        //Movimentação de Janeiro
        $this->dados['inicialMes'] = $ano . "-" ."01"."-01";
        $this->dados['finalMes'] = $ano . "-" ."01"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as janeiro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['janeiro'] = $resultado['janeiro'];
        
        
        //Movimentação de fevereiro
        $this->dados['inicialMes'] = $ano . "-" ."02"."-01";
        $this->dados['finalMes'] = $ano . "-" ."02"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as fevereiro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['fevereiro'] = $resultado['fevereiro'];
        
        
        //Movimentação de Março
        $this->dados['inicialMes'] = $ano . "-" ."03"."-01";
        $this->dados['finalMes'] = $ano . "-" ."03"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as marco FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['marco'] = $resultado['marco'];
        
        
        //Movimentação de Abril
        $this->dados['inicialMes'] = $ano . "-" ."04"."-01";
        $this->dados['finalMes'] = $ano . "-" ."04"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as abril FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['abril'] = $resultado['abril'];
        
        
        //Movimentação de Maio
        $this->dados['inicialMes'] = $ano . "-" ."05"."-01";
        $this->dados['finalMes'] = $ano . "-" ."05"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as maio FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['maio'] = $resultado['maio'];
        
        
        //Movimentação de Junho
        $this->dados['inicialMes'] = $ano . "-" ."06"."-01";
        $this->dados['finalMes'] = $ano . "-" ."06"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as junho FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['junho'] = $resultado['junho'];
        
        
        //Movimentação de Julho
        $this->dados['inicialMes'] = $ano . "-" ."07"."-01";
        $this->dados['finalMes'] = $ano . "-" ."07"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as julho FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['julho'] = $resultado['julho'];
        
        //Movimentação de Agosto
        $this->dados['inicialMes'] = $ano . "-" ."08"."-01";
        $this->dados['finalMes'] = $ano . "-" ."08"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as agosto FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['agosto'] = $resultado['agosto'];
        
        //Movimentação de Setembro
        $this->dados['inicialMes'] = $ano . "-" ."09"."-01";
        $this->dados['finalMes'] = $ano . "-" ."09"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as setembro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['setembro'] = $resultado['setembro'];
        
        //Movimentação de Outobro
        $this->dados['inicialMes'] = $ano . "-" ."10"."-01";
        $this->dados['finalMes'] = $ano . "-" ."10"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as outobro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['outobro'] = $resultado['outobro'];
        
        //Movimentação de Novembro
        $this->dados['inicialMes'] = $ano . "-" ."11"."-01";
        $this->dados['finalMes'] = $ano . "-" ."11"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as novembro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['novembro'] = $resultado['novembro'];
        
        //Movimentação de Dezembro
        $this->dados['inicialMes'] = $ano . "-" ."12"."-01";
        $this->dados['finalMes'] = $ano . "-" ."12"."-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as dezembro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado =  $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['dezembro'] = $resultado['dezembro'];
        
        //var_dump($resultado);
        return $this->dados;
    }

}

