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
class AdmsGraficos extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    public function dadosGrafico() {
        $mes = date("m");
        $ano = date("Y");
        //Movimentação de Janeiro
        $this->dados['inicialMes'] = $ano . "-" . "01" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "01" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as janeiro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['janeiro'] = $resultado['janeiro'];


        //Movimentação de fevereiro
        $this->dados['inicialMes'] = $ano . "-" . "02" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "02" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as fevereiro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['fevereiro'] = $resultado['fevereiro'];


        //Movimentação de Março
        $this->dados['inicialMes'] = $ano . "-" . "03" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "03" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as marco FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['marco'] = $resultado['marco'];


        //Movimentação de Abril
        $this->dados['inicialMes'] = $ano . "-" . "04" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "04" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as abril FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['abril'] = $resultado['abril'];


        //Movimentação de Maio
        $this->dados['inicialMes'] = $ano . "-" . "05" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "05" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as maio FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['maio'] = $resultado['maio'];


        //Movimentação de Junho
        $this->dados['inicialMes'] = $ano . "-" . "06" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "06" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as junho FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['junho'] = $resultado['junho'];


        //Movimentação de Julho
        $this->dados['inicialMes'] = $ano . "-" . "07" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "07" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as julho FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['julho'] = $resultado['julho'];

        //Movimentação de Agosto
        $this->dados['inicialMes'] = $ano . "-" . "08" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "08" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as agosto FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['agosto'] = $resultado['agosto'];

        //Movimentação de Setembro
        $this->dados['inicialMes'] = $ano . "-" . "09" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "09" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as setembro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['setembro'] = $resultado['setembro'];

        //Movimentação de Outobro
        $this->dados['inicialMes'] = $ano . "-" . "10" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "10" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as outobro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['outobro'] = $resultado['outobro'];

        //Movimentação de Novembro
        $this->dados['inicialMes'] = $ano . "-" . "11" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "11" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as novembro FROM movimentacao WHERE  data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['novembro'] = $resultado['novembro'];

        //Movimentação de Dezembro
        $this->dados['inicialMes'] = $ano . "-" . "12" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "12" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as dezembro FROM movimentacao WHERE data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['dezembro'] = $resultado['dezembro'];

        //var_dump($resultado);
        return $this->dados;
    }

    public function dadosGraficoMoviEntrada() {
        $mes = date("m");
        $ano = date("Y");
        //Movimentação de Janeiro
        $this->dados['inicialMes'] = $ano . "-" . "01" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "01" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as janeiro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['janeiro'] = $resultado['janeiro'];


        //Movimentação de fevereiro
        $this->dados['inicialMes'] = $ano . "-" . "02" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "02" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as fevereiro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['fevereiro'] = $resultado['fevereiro'];


        //Movimentação de Março
        $this->dados['inicialMes'] = $ano . "-" . "03" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "03" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as marco FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['marco'] = $resultado['marco'];


        //Movimentação de Abril
        $this->dados['inicialMes'] = $ano . "-" . "04" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "04" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as abril FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['abril'] = $resultado['abril'];


        //Movimentação de Maio
        $this->dados['inicialMes'] = $ano . "-" . "05" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "05" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as maio FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['maio'] = $resultado['maio'];


        //Movimentação de Junho
        $this->dados['inicialMes'] = $ano . "-" . "06" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "06" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as junho FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['junho'] = $resultado['junho'];


        //Movimentação de Julho
        $this->dados['inicialMes'] = $ano . "-" . "07" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "07" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as julho FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['julho'] = $resultado['julho'];

        //Movimentação de Agosto
        $this->dados['inicialMes'] = $ano . "-" . "08" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "08" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as agosto FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['agosto'] = $resultado['agosto'];

        //Movimentação de Setembro
        $this->dados['inicialMes'] = $ano . "-" . "09" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "09" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as setembro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['setembro'] = $resultado['setembro'];

        //Movimentação de Outobro
        $this->dados['inicialMes'] = $ano . "-" . "10" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "10" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as outobro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['outobro'] = $resultado['outobro'];

        //Movimentação de Novembro
        $this->dados['inicialMes'] = $ano . "-" . "11" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "11" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as novembro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['novembro'] = $resultado['novembro'];

        //Movimentação de Dezembro
        $this->dados['inicialMes'] = $ano . "-" . "12" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "12" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as dezembro FROM movimentacao WHERE tipo = 'Entrada' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['dezembro'] = $resultado['dezembro'];

        //var_dump($resultado);
        return $this->dados;
    }

    public function dadosGraficoMoviSaida() {
        $mes = date("m");
        $ano = date("Y");
        //Movimentação de Janeiro
        $this->dados['inicialMes'] = $ano . "-" . "01" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "01" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as janeiro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['janeiro'] = $resultado['janeiro'];


        //Movimentação de fevereiro
        $this->dados['inicialMes'] = $ano . "-" . "02" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "02" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as fevereiro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['fevereiro'] = $resultado['fevereiro'];


        //Movimentação de Março
        $this->dados['inicialMes'] = $ano . "-" . "03" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "03" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as marco FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['marco'] = $resultado['marco'];


        //Movimentação de Abril
        $this->dados['inicialMes'] = $ano . "-" . "04" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "04" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as abril FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['abril'] = $resultado['abril'];


        //Movimentação de Maio
        $this->dados['inicialMes'] = $ano . "-" . "05" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "05" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as maio FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['maio'] = $resultado['maio'];


        //Movimentação de Junho
        $this->dados['inicialMes'] = $ano . "-" . "06" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "06" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as junho FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['junho'] = $resultado['junho'];


        //Movimentação de Julho
        $this->dados['inicialMes'] = $ano . "-" . "07" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "07" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as julho FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['julho'] = $resultado['julho'];

        //Movimentação de Agosto
        $this->dados['inicialMes'] = $ano . "-" . "08" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "08" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as agosto FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['agosto'] = $resultado['agosto'];

        //Movimentação de Setembro
        $this->dados['inicialMes'] = $ano . "-" . "09" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "09" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as setembro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['setembro'] = $resultado['setembro'];

        //Movimentação de Outobro
        $this->dados['inicialMes'] = $ano . "-" . "10" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "10" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as outobro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['outobro'] = $resultado['outobro'];

        //Movimentação de Novembro
        $this->dados['inicialMes'] = $ano . "-" . "11" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "11" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as novembro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['novembro'] = $resultado['novembro'];

        //Movimentação de Dezembro
        $this->dados['inicialMes'] = $ano . "-" . "12" . "-01";
        $this->dados['finalMes'] = $ano . "-" . "12" . "-31";
        $result = $this->conn->prepare("SELECT SUM(valor) as dezembro FROM movimentacao WHERE tipo = 'Saída' AND data between '{$this->dados['inicialMes']}' AND '{$this->dados['finalMes']}'");
        $result->execute();
        $resultado = $result->fetch(\PDO::FETCH_ASSOC);
        $this->dados['dezembro'] = $resultado['dezembro'];

        //var_dump($resultado);
        return $this->dados;
    }

    public function dadosServicoMaisPrestado() {
        //O Valor de 100%
        $result = $this->conn->prepare("SELECT count(tipo_servico) as total FROM dadosorcamento WHERE status != 'Aberto'");
        $result->execute();
        $resultado = $result->fetch(PDO::FETCH_ASSOC);
        //O 100% para fazer a Divisão

        $this->dados['total'] = $resultado['total'];
        // Pegar Os Nomes de Cada Serviço

        $result = $this->conn->prepare("SELECT DISTINCT tipo_servico FROM dadosorcamento WHERE status != 'Aberto'");
        $result->execute();
        $resultado = $result->fetchAll(PDO::FETCH_ASSOC);


        for ($index = 0; $index < count($resultado); $index++) {
            //$this->dados[] = $resultado[$index];

            $nome_servico = $resultado[$index];

            //echo $nome_servico['tipo_servico'];
            $result = $this->conn->prepare("SELECT count(tipo_servico) as valor FROM dadosorcamento WHERE tipo_servico = '{$nome_servico['tipo_servico']}' AND status != 'Aberto'");
            $result->execute();
            $resultado1 = $result->fetch();
            $this->dados['nome'][] = $nome_servico['tipo_servico'];
            //Valor Em Percetagem
            $valor = ($resultado1['valor'] * 100) / $this->dados['total'];

            $this->dados['valor'][] = number_format($valor, 1);
        }
        $min = 0;
        $valorx = 0;
        $max = 0;

        if (isset($this->dados['valor']) && isset($this->dados['nome'])) {
            for ($index1 = 0; $index1 < count($this->dados['valor']); $index1++) {
                $valorx = $this->dados['valor'][$index1];
                //$min = $this->dados['valor'][$index1];
                if ($max < $valorx) {
                    $max = $valorx;
                    $nomemax = $this->dados['nome'][$index1];
                    $_SESSION['msg_gerafico1'] = "O Serviço Mais prestado Ou Mais Solicitado Na Oficina é o De<strong class='text-success'> " . $nomemax . " </strong> com <strong> " . $max . " % </strong> de Frequência Em Relação Ao Outros Seviços. E";
                } elseif ($max > $min) {
                    $min = $valorx;
                    $nomemin = $this->dados['nome'][$index1];
                    $_SESSION['msg_gerafico2'] = " O Serviço Menos prestado Ou Menos Solicitado Na Oficina é o De <strong class='text-danger'>" . $nomemin . "</strong> com <strong>" . $min . " %</strong> de Frequência Em Relação Ao Outros Seviços";
                } elseif ($max == $min) {
                    $max = $valorx;
                    $nomemax = $this->dados['nome'][$index1];
                    $min = $valorx;
                    $nomemin = $this->dados['nome'][$index1];
                    //$_SESSION['msg_gerafico2'] = " Os Serviço Menos prestado Ou Menos Solicitado Na Oficina e  De <strong class='text-danger'>" . $nomemin . "</strong> com <strong>" . $min . " %</strong> de Frequência Em Relação Ao Outros Seviços";
                    $_SESSION['msg_gerafico1'] = "Os Serviço Mais prestado Ou Mais Solicitados Na Oficina São De<strong class='text-success'> " . $nomemax . " </strong> com <strong> " . $max . " % </strong> e  De <strong class='text-danger'>" . $nomemin . "</strong> com <strong>" . $min . " %</strong> de Frequência Em Relação Ao Outros Seviços. E";
                }
            }
        }

        return $this->dados;
    }

}
