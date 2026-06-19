<?php

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
if (isset($this->dados['form'])) {
    $valorForm = $this->dados['form'];
    //var_dump($valorForm);
}
if (isset($_SESSION['idlogado'])) {
    //echo "<br>id Logado " . $_SESSION['idlogado'];
    //var_dump($_SESSION);
}
?>
<!-- Begin Page Content -->


<!-- Logout Modal-->
<div class="modal fade" id="novoveiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar veiculo</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="matricula">Matricula</label>
                            <input type="text" id="matricula" class="form-control" name="matricula" placeholder="Digete O Nº Da Matricula" value="<?php
                            if (isset($valorForm['matricula'])) {
                                echo $valorForm['matricula'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Campo Obrigatorio.
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationTooltip04">Cliente Nif</label>
                            <select class="custom-select" id="validationTooltip04" name="idclientes" required>
                                <option selected disabled value="">Choose...</option>
                                <?php
                                if (isset($this->dadosAlter)) {
                                    foreach ($this->dadosAlter as $valor) {
                                        ?>
                                        <option value="<?php echo $valor['idclientes']; ?>"><?php echo $valor['nif']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <div class="invalid-tooltip">
                                Please select a valid state.
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="dataregisto">Data do Primeiro Registo</label>
                            <input type="date" id="dataregisto" class="form-control" name="dataregisto" value="<?php
                            if (isset($valorForm['dataregisto'])) {
                                echo $valorForm['dataregisto'];
                            }
                            ?>" required>
                            <div class="invalid-feedback">
                                Preecha o Campo porfavor.
                            </div>
                        </div>
                    </div>  
                    <fieldset>
                        <legend class="text-muted"> <code class="text-muted" title="Caracteristica do Equipamento">Caracteristica do Equipamento </code></legend>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="marca">Marca</label>
                                <input type="text" class="form-control" id="marca" placeholder="Digete A Marca Do Carro"  name="marca" value="<?php
                                if (isset($valorForm['marca'])) {
                                    echo $valorForm['marca'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="modelo">Modelo</label>
                                <input type="text" class="form-control" id="modelo" placeholder="Digete O Modelo Do Carro" name="modelo" value="<?php
                                if (isset($valorForm['modelo'])) {
                                    echo $valorForm['modelo'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nmotor">Nº Do Motor</label>
                                <input type="text" class="form-control" id="nmotor" placeholder="Digete A Nº do Motor Do Carro"  name="nmotor" value="<?php
                                if (isset($valorForm['nmotor'])) {
                                    echo $valorForm['nmotor'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="nquadro">Nº Quadro</label>
                                <input type="text" class="form-control" id="nquadro" placeholder="Digete O Nº do Quadro Do Carro" name="nquadro" value="<?php
                                if (isset($valorForm['nquadro'])) {
                                    echo $valorForm['nquadro'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cor">Cor</label>
                                <input type="text" class="form-control" id="cor" placeholder="Digete A Cor Do Carro"  name="cor" value="<?php
                                if (isset($valorForm['cor'])) {
                                    echo $valorForm['cor'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="pesobruto">Peso Bruto</label>
                                <input type="text" class="form-control" id="pesobruto" placeholder="Digete A Peso Bruto Do Carro"  name="pesobruto" value="<?php
                                if (isset($valorForm['pesobruto'])) {
                                    echo $valorForm['pesobruto'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="medidapeneu">Medidas Do Pneumáticos</label>
                                <input type="text" class="form-control" id="medidapeneu" placeholder="Digete O Medidas Do Pneumáticos  Do Carro" name="medidapeneu" value="<?php
                                if (isset($valorForm['medidapeneu'])) {
                                    echo $valorForm['medidapeneu'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cilindrada">Cilindrada</label>
                                <input type="text" class="form-control" id="cilidrada" placeholder="Digete A Cilindrada Do Carro"  name="cilindrada" value="<?php
                                if (isset($valorForm['cilidrada'])) {
                                    echo $valorForm['cilindrada'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ncilindros">Nº de Cilindros</label>
                                <input type="number" class="form-control" id="ncilidros" placeholder="Digete A Nº Cilidros Do Carro"  name="ncilindros" min="1" value="<?php
                                if (isset($valorForm['ncilindros'])) {
                                    echo $valorForm['ncilindros'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="tipocaixa">Tipo De Caixa</label>
                                <input type="text" class="form-control" id="tipocaixa" placeholder="Digete O Tipo de Caixa Do Carro" name="tipocaixa" value="<?php
                                if (isset($valorForm['tipocaixa'])) {
                                    echo $valorForm['tipocaixa'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="combustivel">Combustível</label>
                                <input type="text" class="form-control" id="combustivel" placeholder="Digete O Combustível Do Carro"  name="combustivel" value="<?php
                                if (isset($valorForm['combustivel'])) {
                                    echo $valorForm['combustivel'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="distanciaeixo">Distância Entre Eixos</label>
                                <input type="text" class="form-control" id="distanciaeixo" placeholder="Digete A Distância Entre Eixos Do Carro" name="distanciaeixo" value="<?php
                                if (isset($valorForm['distanciaeixo'])) {
                                    echo $valorForm['distanciaeixo'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lotacao">Lotação</label>
                                <input type="number" class="form-control" id="lotacao" placeholder="Digete A Lotação Do Carro"  min="1" name="lotacao" value="<?php
                                if (isset($valorForm['lotacao'])) {
                                    echo $valorForm['lotacao'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">
                                    Preecha O Campo.
                                </div>
                            </div>
                        </div>  
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary text-white" name="btnCdsEquipamento">Salvar</button>
                </div>
            </form> 
        </div>
    </div>
</div>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <!-- Page Heading -->
    <div class="row mt-4 mb-4">
        <a type="button" class="btn-primary btn-sm ml-3 d-none d-md-block text-white" href="" data-toggle="modal" data-target="#novoveiculo"> Novo veiculo</a>
        <!--<a type="button" class="btn-primary btn-sm ml-3 d-block d-lg-none"> + </a>-->
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">veiculo</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Nº de Série</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Cor</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cliente</th>
                            <th>Nº de Série</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Cor</th>
                            <th>Acção</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (isset($this->dados)) {
                            for ($index = 0; $index < count($this->dados); $index++) {
                                $valorForm = $this->dados[$index];
                                ?>
                                <tr>
                                    <td><?php echo $valorForm['nome']." ".$valorForm['sobrenome']; ?></td>
                                    <td><?php echo $valorForm['matricula']; ?></td>
                                    <td><?php echo $valorForm['marca']; ?></td>
                                    <td><?php echo $valorForm['modelo']; ?></td>
                                    <td><?php echo $valorForm['cor']; ?></td>
                                    <td>
                                        <a href="<?php echo $valorForm['idveiculo']; ?>" data-toggle="modal" data-target="#edit<?php echo $valorForm['idveiculo']; ?>"><i class="icofont icofont-edit px-1"></i></a>
                                        <a href="<?php echo $valorForm['idveiculo']; ?>" data-toggle="modal" data-target="#delete<?php echo $valorForm['idveiculo']; ?>"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                        <a href="<?php echo $valorForm['idveiculo']; ?>" data-toggle="modal" data-target="#info<?php echo $valorForm['idveiculo']; ?>"><i class="icofont icofont-warning-alt text-info px-1"></i></a>
                                        <a href="<?php echo $valorForm['idveiculo']; ?>" data-toggle="modal" data-target="#info<?php echo $valorForm['idveiculo']; ?>" title="Relatório do Serviço do Carro"><i class="icofont icofont-envelope-open text-info px-1"></i></a>
                                        <a href="<?php echo $valorForm['idveiculo']; ?>" data-toggle="modal" data-target="#info<?php echo $valorForm['idveiculo']; ?>" title="Relatório do Ultimo Serviço"><i class="icofont icofont-envelope text-info"></i></a>
                                    </td>
                                </tr>
                                <!--  Modal Deletar-->
                            <div class="modal fade" id="delete<?php echo $valorForm['idveiculo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Apagar?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Clica "Sim" Para Pagar Esse veiculo <?php echo "Nº de Série: " . $valorForm['matricula'] . " Marca: " . $valorForm['marca']; ?>.</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                                <input type="hidden" name="idveiculo" value="<?php echo $valorForm['idveiculo']; ?>">
                                                <button class="btn btn-primary" name="btnDeleteEquipamento">Sim</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Modal Deletar-->
                            <div class="modal fade" id="edit<?php echo $valorForm['idveiculo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Editar?</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="matricula">Matricula</label>
                                                        <input type="text" id="matricula" class="form-control" name="matricula" placeholder="Digete O Nº Da Matricula" value="<?php
                                                        if (isset($valorForm['matricula'])) {
                                                            echo $valorForm['matricula'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Campo Obrigatorio.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label for="validationTooltip04">Cliente Nif</label>
                                                        <select class="custom-select" id="validationTooltip04" name="idclientes" required>
                                                            <option selected disabled value="">Choose...</option>
                                                            <?php
                                                            if (isset($this->dadosAlter)) {
                                                                foreach ($this->dadosAlter as $valor) {
                                                                    ?>
                                                                    <option value="<?php echo $valor['idclientes']; ?>"><?php echo $valor['nif']; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="invalid-tooltip">
                                                            Please select a valid state.
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="dataregisto">Data do Primeiro Registo</label>
                                                        <input type="date" id="dataregisto" class="form-control" name="dataregisto" value="<?php
                                                        if (isset($valorForm['dataregisto'])) {
                                                            echo $valorForm['dataregisto'];
                                                        }
                                                        ?>" required>
                                                        <div class="invalid-feedback">
                                                            Preecha o Campo porfavor.
                                                        </div>
                                                    </div>
                                                </div>  
                                                <fieldset>
                                                    <legend class="text-muted"> <code class="text-muted" title="Caracteristica do Equipamento">Caracteristica do Equipamento </code></legend>
                                                    <div class="row">
                                                        <div class="col-md-3 mb-3">
                                                            <label for="marca">Marca</label>
                                                            <input type="text" class="form-control" id="marca" placeholder="Digete A Marca Do Carro"  name="marca" value="<?php
                                                            if (isset($valorForm['marca'])) {
                                                                echo $valorForm['marca'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="modelo">Modelo</label>
                                                            <input type="text" class="form-control" id="modelo" placeholder="Digete O Modelo Do Carro" name="modelo" value="<?php
                                                            if (isset($valorForm['modelo'])) {
                                                                echo $valorForm['modelo'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="nmotor">Nº Do Motor</label>
                                                            <input type="text" class="form-control" id="nmotor" placeholder="Digete A Nº do Motor Do Carro"  name="nmotor" value="<?php
                                                            if (isset($valorForm['nmotor'])) {
                                                                echo $valorForm['nmotor'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="nquadro">Nº Quadro</label>
                                                            <input type="text" class="form-control" id="nquadro" placeholder="Digete O Nº do Quadro Do Carro" name="nquadro" value="<?php
                                                            if (isset($valorForm['nquadro'])) {
                                                                echo $valorForm['nquadro'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="cor">Cor</label>
                                                            <input type="text" class="form-control" id="cor" placeholder="Digete A Cor Do Carro"  name="cor" value="<?php
                                                            if (isset($valorForm['cor'])) {
                                                                echo $valorForm['cor'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="pesobruto">Peso Bruto</label>
                                                            <input type="text" class="form-control" id="pesobruto" placeholder="Digete A Peso Bruto Do Carro"  name="pesobruto" value="<?php
                                                            if (isset($valorForm['pesobruto'])) {
                                                                echo $valorForm['pesobruto'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="medidapeneu">Medidas Do Pneumáticos</label>
                                                            <input type="text" class="form-control" id="medidapeneu" placeholder="Digete O Medidas Do Pneumáticos  Do Carro" name="medidapeneu" value="<?php
                                                            if (isset($valorForm['medidapeneu'])) {
                                                                echo $valorForm['medidapeneu'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="cilindrada">Cilindrada</label>
                                                            <input type="text" class="form-control" id="cilidrada" placeholder="Digete A Cilindrada Do Carro"  name="cilindrada" value="<?php
                                                            if (isset($valorForm['cilindrada'])) {
                                                                echo $valorForm['cilindrada'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="ncilindros">Nº de Cilindros</label>
                                                            <input type="number" class="form-control" id="ncilidros" placeholder="Digete A Nº Cilidros Do Carro"  name="ncilindros" min="1" value="<?php
                                                            if (isset($valorForm['ncilindros'])) {
                                                                echo $valorForm['ncilindros'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="tipocaixa">Tipo De Caixa</label>
                                                            <input type="text" class="form-control" id="tipocaixa" placeholder="Digete O Tipo de Caixa Do Carro" name="tipocaixa" value="<?php
                                                            if (isset($valorForm['tipocaixa'])) {
                                                                echo $valorForm['tipocaixa'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="combustivel">Combustível</label>
                                                            <input type="text" class="form-control" id="combustivel" placeholder="Digete O Combustível Do Carro"  name="combustivel" value="<?php
                                                            if (isset($valorForm['combustivel'])) {
                                                                echo $valorForm['combustivel'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="distanciaeixo">Distância Entre Eixos</label>
                                                            <input type="text" class="form-control" id="distanciaeixo" placeholder="Digete A Distância Entre Eixos Do Carro" name="distanciaeixo" value="<?php
                                                            if (isset($valorForm['distanciaeixo'])) {
                                                                echo $valorForm['distanciaeixo'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="lotacao">Lotação</label>
                                                            <input type="number" class="form-control" id="lotacao" placeholder="Digete A Lotação Do Carro" name="lotacao" min="1" value="<?php
                                                            if (isset($valorForm['lotacao'])) {
                                                                echo $valorForm['lotacao'];
                                                            }
                                                            ?>" required>
                                                            <div class="invalid-feedback">
                                                                Preecha O Campo.
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </fieldset>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="idveiculo" value="<?php echo $valorForm['idveiculo']; ?>">
                                                <button class="btn btn-primary" name="btnEditEquipamento">Sim</button>
                                            </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!--  Modal Descrição do veiculos-->
                            <div class="modal fade" id="info<?php echo $valorForm['idveiculo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Descrição do veiculos</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <div class="container">
                                                            <strong>Cliente: </strong><?php echo $valorForm['nome']." ".$valorForm['sobrenome']; ?><br> 
                                                            <strong>Matricula: </strong><?php echo $valorForm['matricula']; ?><br>
                                                            <strong>Marca: </strong><?php echo $valorForm['marca']; ?><br>
                                                            <strong>Modelo :</strong><?php echo $valorForm['modelo']; ?><br>
                                                            <strong>Cor: </strong><?php echo $valorForm['cor']; ?><br>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


