<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
if (isset($this->dados['form'])) {
    $valorForm = $this->dados['form'];
}
?>
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-door-open mr-2"></i>Entradas de Equipamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Marca / Modelo</th>
                            <th>Nº de Série</th>
                            <th>Tipo</th>
                            <th>Cliente</th>
                            <th>Técnico</th>
                            <th>Estado</th>
                            <th>Data Entrada</th>
                            <th>Serviço</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Marca / Modelo</th>
                            <th>Nº de Série</th>
                            <th>Tipo</th>
                            <th>Cliente</th>
                            <th>Técnico</th>
                            <th>Estado</th>
                            <th>Data Entrada</th>
                            <th>Serviço</th>
                            <th>Acção</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (!empty($this->dados) && is_array($this->dados)) {
                            foreach ($this->dados as $valorForm) {
                                $estadoBadge = [
                                    'Recebido'         => 'secondary',
                                    'Em Diagnóstico'   => 'info',
                                    'Aguardando Peças' => 'warning',
                                    'Em Reparação'     => 'primary',
                                    'Concluído'        => 'success',
                                    'Entregue'         => 'dark',
                                ];
                                $badgeColor = $estadoBadge[$valorForm['estado'] ?? ''] ?? 'secondary';

                                $clienteNome = htmlspecialchars(trim(($valorForm['cliente_nome'] ?? '') . ' ' . ($valorForm['cliente_sobrenome'] ?? '')));
                                if (empty($clienteNome)) {
                                    $clienteNome = '—';
                                }

                                $tecnicoNome = htmlspecialchars(trim(($valorForm['tecnico_nome'] ?? '') . ' ' . ($valorForm['tecnico_sobrenome'] ?? '')));
                                if (empty($tecnicoNome)) {
                                    $tecnicoNome = '—';
                                }

                                $numeroSerie = htmlspecialchars($valorForm['numero_serie'] ?? $valorForm['matricula'] ?? '—');
                                $tipo = htmlspecialchars($valorForm['tipo_equipamento'] ?? '—');
                                $marca = htmlspecialchars($valorForm['marca'] ?? '');
                                $modelo = htmlspecialchars($valorForm['modelo'] ?? '');
                                $estado = htmlspecialchars($valorForm['estado'] ?? 'Recebido');
                                ?>
                                <tr>
                                    <td><?= $marca ?><?= $modelo ? " / $modelo" : '' ?></td>
                                    <td><strong><?= $numeroSerie ?></strong></td>
                                    <td><?= $tipo ?></td>
                                    <td><?= $clienteNome ?></td>
                                    <td><?= $tecnicoNome ?></td>
                                    <td><span class="badge badge-<?= $badgeColor ?>"><?= $estado ?></span></td>
                                    <td><?= htmlspecialchars($valorForm['data_entrada'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($valorForm['servico'] ?? '') ?></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#delete<?= $valorForm['id'] ?>" title="Eliminar">
                                            <i class="icofont icofont-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Eliminar -->
                                <div class="modal fade" id="delete<?= $valorForm['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirmar Eliminação</h5>
                                                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                Tens a certeza que queres remover a entrada do equipamento
                                                <strong><?= $numeroSerie ?></strong> (<?= $marca ?>)?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <form action="" method="post" novalidate>
                                                    <input type="hidden" name="identrada_veiculo" value="<?= $valorForm['id'] ?>">
                                                    <button class="btn btn-danger" name="btnDeleteEquipamento">Sim, Eliminar</button>
                                                </form>
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

