<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
// Garantir que $valorForm é um array para evitar acessar offsets de uma string
$valorForm = [];
if (isset($this->dados['form']) && is_array($this->dados['form'])) {
    $valorForm = $this->dados['form'];
}
?>
<!-- Modal: Cadastrar Equipamento -->
<div class="modal fade" id="novoequipamento" tabindex="-1" role="dialog" aria-labelledby="modalEquipLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEquipLabel"><i class="fas fa-laptop mr-2"></i>Registar Equipamento</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="numero_serie">Nº de Série <span class="text-danger">*</span></label>
                            <input type="text" id="numero_serie" class="form-control" name="numero_serie"
                                placeholder="Ex: SN-123456789" value="<?= isset($valorForm['numero_serie']) ? htmlspecialchars($valorForm['numero_serie']) : '' ?>" required>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="imei">IMEI <small class="text-muted">(telemóveis/tablets)</small></label>
                            <input type="text" id="imei" class="form-control" name="imei"
                                placeholder="Ex: 358043056789012" value="<?= isset($valorForm['imei']) ? htmlspecialchars($valorForm['imei']) : '' ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="idclientes">Cliente <span class="text-danger">*</span></label>
                            <select class="custom-select" id="idclientes" name="idclientes" required>
                                <option value="">Selecione...</option>
                                <?php
                                if (!empty($this->dadosAlter) && is_array($this->dadosAlter)) {
                                    $clienteSel = isset($valorForm['idclientes']) ? (int)$valorForm['idclientes'] : (int)($this->dados['default_client_id'] ?? 0);
                                    foreach ($this->dadosAlter as $cliente) {
                                        $sel = ((int)($cliente['idclientes'] ?? 0) === $clienteSel) ? 'selected' : '';
                                        echo '<option value="' . (int)$cliente['idclientes'] . '" ' . $sel . '>' . htmlspecialchars($cliente['nif'] . ' — ' . $cliente['nome'] . ' ' . $cliente['sobrenome']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Selecione o cliente.</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tipo_equipamento">Tipo de Equipamento <span class="text-danger">*</span></label>
                            <select class="custom-select" id="tipo_equipamento" name="tipo_equipamento" required>
                                <option value="">Selecione...</option>
                                <?php
                                $tipos = ['Computador', 'Portátil', 'Impressora', 'Servidor', 'Equipamento de Rede', 'Telemóvel', 'Tablet', 'Monitor', 'Outro'];
                                $tipoSel = isset($valorForm['tipo_equipamento']) ? $valorForm['tipo_equipamento'] : '';
                                foreach ($tipos as $t) {
                                    $sel = ($tipoSel == $t) ? 'selected' : '';
                                    echo "<option value=\"$t\" $sel>$t</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Selecione o tipo.</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="marca">Marca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="marca" name="marca"
                                placeholder="Ex: Dell, HP, Apple" value="<?= isset($valorForm['marca']) ? htmlspecialchars($valorForm['marca']) : '' ?>" required>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="modelo">Modelo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modelo" name="modelo"
                                placeholder="Ex: Inspiron 15, MacBook Pro" value="<?= isset($valorForm['modelo']) ? htmlspecialchars($valorForm['modelo']) : '' ?>" required>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="estado">Estado Actual <span class="text-danger">*</span></label>
                            <select class="custom-select" id="estado" name="estado" required>
                                <?php
                                $estados = ['Recebido', 'Em Diagnóstico', 'Aguardando Peças', 'Em Reparação', 'Concluído', 'Entregue'];
                                $estadoSel = isset($valorForm['estado']) ? $valorForm['estado'] : 'Recebido';
                                foreach ($estados as $e) {
                                    $sel = ($estadoSel == $e) ? 'selected' : '';
                                    echo "<option value=\"$e\" $sel>$e</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Campo obrigatório.</div>
                        </div>
                    </div>
                  
                    <fieldset class="border rounded p-3 mb-2">
                        <legend class="w-auto px-2 text-muted small">Diagnóstico Técnico</legend>
                        <div class="form-group">
                            <label for="defeito_reportado">Defeito Reportado pelo Cliente</label>
                            <textarea class="form-control" id="defeito_reportado" name="defeito_reportado" rows="2"
                                placeholder="Descreva o problema relatado pelo cliente..."><?= isset($valorForm['defeito_reportado']) ? htmlspecialchars($valorForm['defeito_reportado']) : '' ?></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="border rounded p-3 mb-2">
                        <legend class="w-auto px-2 text-muted small">Dados Patrimoniais</legend>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="categoria">Categoria</label>
                                <select class="custom-select" id="categoria" name="idcategoria_equipamento">
                                    <option value="">Selecione...</option>
                                    <?php
                                    if (isset($this->dadosPaginacao)) {
                                        foreach ($this->dadosPaginacao as $cat) {
                                            $sel = (isset($valorForm['idcategoria_equipamento']) && $valorForm['idcategoria_equipamento'] == $cat['idcategoria_equipamento']) ? 'selected' : '';
                                            echo '<option value="' . (int)$cat['idcategoria_equipamento'] . '" ' . $sel . '>' . htmlspecialchars($cat['nome']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo"
                                    value="<?= isset($valorForm['codigo']) ? htmlspecialchars($valorForm['codigo']) : '' ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="patrimonio">Património</label>
                                <input type="text" class="form-control" id="patrimonio" name="patrimonio"
                                    value="<?= isset($valorForm['patrimonio']) ? htmlspecialchars($valorForm['patrimonio']) : '' ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nome_equip">Nome do Equipamento</label>
                                <input type="text" class="form-control" id="nome_equip" name="nome"
                                    placeholder="Ex: PC Sala de Informática 3"
                                    value="<?= isset($valorForm['nome']) ? htmlspecialchars($valorForm['nome']) : '' ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="departamento">Departamento</label>
                                <input type="text" class="form-control" id="departamento" name="departamento"
                                    value="<?= isset($valorForm['departamento']) ? htmlspecialchars($valorForm['departamento']) : '' ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="localizacao">Localização</label>
                                <input type="text" class="form-control" id="localizacao" name="localizacao"
                                    value="<?= isset($valorForm['localizacao']) ? htmlspecialchars($valorForm['localizacao']) : '' ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="data_aquisicao">Data de Aquisição</label>
                                <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao"
                                    value="<?= isset($valorForm['data_aquisicao']) ? htmlspecialchars($valorForm['data_aquisicao']) : '' ?>">
                            </div>
                            <div class="form-group col-md-8">
                                <label for="observacoes">Observações</label>
                                <input type="text" class="form-control" id="observacoes" name="observacoes"
                                    value="<?= isset($valorForm['observacoes']) ? htmlspecialchars($valorForm['observacoes']) : '' ?>">
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" name="btnCdsEquipamento"><i class="fas fa-save mr-1"></i>Registar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="container-fluid">
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <a type="button" class="btn btn-primary btn-sm" href="" data-toggle="modal" data-target="#novoequipamento">
                <i class="fas fa-plus mr-1"></i> Novo Equipamento
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-laptop mr-2"></i>Equipamentos Registados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Cliente</th>
                            <th>NIF</th>
                            <th>Nº de Série</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Estado</th>
                            <th>Acção</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Cliente</th>
                            <th>NIF</th>
                            <th>Nº de Série</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Estado</th>
                            <th>Acção</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if (!empty($this->dados) && is_array($this->dados)) {
                            foreach ($this->dados as $valorForm) {
                                if (!is_array($valorForm)) {
                                    // ignorar entradas inesperadas que não sejam arrays
                                    continue;
                                }
                                $id = $valorForm['idequipamento'] ?? $valorForm['idveiculo'] ?? 0;
                                $estadoBadge = [
                                    'Recebido'       => 'secondary',
                                    'Em Diagnóstico' => 'info',
                                    'Aguardando Peças' => 'warning',
                                    'Em Reparação'   => 'primary',
                                    'Concluído'      => 'success',
                                    'Entregue'       => 'dark',
                                ];
                                $badgeColor = $estadoBadge[$valorForm['estado'] ?? ''] ?? 'secondary';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($valorForm['nome'] . ' ' . $valorForm['sobrenome']) ?></td>
                                    <td><?= htmlspecialchars($valorForm['nif']) ?></td>
                                    <td><strong><?= htmlspecialchars($valorForm['numero_serie'] ?? $valorForm['matricula'] ?? '') ?></strong></td>
                                    <td><?= htmlspecialchars($valorForm['tipo_equipamento'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($valorForm['marca']) ?></td>
                                    <td><?= htmlspecialchars($valorForm['modelo']) ?></td>
                                    <td><span class="badge badge-<?= $badgeColor ?>"><?= htmlspecialchars($valorForm['estado'] ?? 'Recebido') ?></span></td>
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#edit<?= $id ?>" title="Editar"><i class="icofont icofont-edit px-1 text-primary"></i></a>
                                        <a href="#" data-toggle="modal" data-target="#delete<?= $id ?>" title="Eliminar"><i class="icofont icofont-trash text-danger px-1"></i></a>
                                        <a href="#" data-toggle="modal" data-target="#info<?= $id ?>" title="Detalhes"><i class="icofont icofont-info-circle text-info px-1"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal Eliminar -->
                                <div class="modal fade" id="delete<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirmar Eliminação</h5>
                                                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                Tens a certeza que queres eliminar o equipamento <strong><?= htmlspecialchars($valorForm['marca'] . ' ' . $valorForm['modelo']) ?></strong>
                                                (Nº Série: <strong><?= htmlspecialchars($valorForm['numero_serie'] ?? $valorForm['matricula'] ?? '') ?></strong>)?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                <form action="" method="post" novalidate>
                                                    <input type="hidden" name="idequipamento" value="<?= $id ?>">
                                                    <input type="hidden" name="idveiculo" value="<?= $id ?>">
                                                    <button class="btn btn-danger" name="btnDeleteEquipamento">Sim, Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="edit<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form action="" method="post" novalidate>
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title">Editar Equipamento</h5>
                                                    <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>Nº de Série <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="numero_serie"
                                                                value="<?= htmlspecialchars($valorForm['numero_serie'] ?? $valorForm['matricula'] ?? '') ?>" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>IMEI</label>
                                                            <input type="text" class="form-control" name="imei"
                                                                value="<?= htmlspecialchars($valorForm['imei'] ?? '') ?>">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Tipo de Equipamento <span class="text-danger">*</span></label>
                                                            <select class="custom-select" name="tipo_equipamento" required>
                                                                <?php
                                                                $tipos = ['Computador', 'Portátil', 'Impressora', 'Servidor', 'Equipamento de Rede', 'Telemóvel', 'Tablet', 'Monitor', 'Outro'];
                                                                foreach ($tipos as $t) {
                                                                    $sel = (($valorForm['tipo_equipamento'] ?? '') == $t) ? 'selected' : '';
                                                                    echo "<option value=\"$t\" $sel>$t</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>Marca <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="marca"
                                                                value="<?= htmlspecialchars($valorForm['marca']) ?>" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Modelo <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="modelo"
                                                                value="<?= htmlspecialchars($valorForm['modelo']) ?>" required>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Estado</label>
                                                            <select class="custom-select" name="estado">
                                                                <?php
                                                                $estados = ['Recebido', 'Em Diagnóstico', 'Aguardando Peças', 'Em Reparação', 'Concluído', 'Entregue'];
                                                                foreach ($estados as $e) {
                                                                    $sel = (($valorForm['estado'] ?? '') == $e) ? 'selected' : '';
                                                                    echo "<option value=\"$e\" $sel>$e</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6 mb-3">
                                                            <label>Cliente (NIF)</label>
                                                            <select class="custom-select" name="idclientes" required>
                                                                <option value="">Selecione...</option>
                                                                <?php
                                                                if (isset($this->dadosAlter)) {
                                                                    foreach ($this->dadosAlter as $valor) {
                                                                        $sel = ($valor['idclientes'] == ($valorForm['idclientes'] ?? '')) ? 'selected' : '';
                                                                        echo '<option value="' . $valor['idclientes'] . '" ' . $sel . '>' . htmlspecialchars($valor['nif'] . ' — ' . $valor['nome'] . ' ' . $valor['sobrenome']) . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Data de Entrada</label>
                                                            <input type="date" class="form-control" name="dataregisto"
                                                                value="<?= htmlspecialchars($valorForm['dataregisto'] ?? '') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Defeito Reportado</label>
                                                        <textarea class="form-control" name="defeito_reportado" rows="2"><?= htmlspecialchars($valorForm['defeito_reportado'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Diagnóstico Técnico</label>
                                                        <textarea class="form-control" name="diagnostico_tecnico" rows="2"><?= htmlspecialchars($valorForm['diagnostico_tecnico'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Garantia da Reparação</label>
                                                        <input type="text" class="form-control" name="garantia_reparacao"
                                                            placeholder="Ex: 90 dias" value="<?= htmlspecialchars($valorForm['garantia_reparacao'] ?? '') ?>">
                                                    </div>
                                                    <fieldset class="border rounded p-3 mb-2">
                                                        <legend class="w-auto px-2 text-muted small">Dados Patrimoniais</legend>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label>Categoria</label>
                                                                <select class="custom-select" name="idcategoria_equipamento">
                                                                    <option value="">Selecione...</option>
                                                                    <?php
                                                                    if (isset($this->dadosPaginacao)) {
                                                                        foreach ($this->dadosPaginacao as $cat) {
                                                                            $sel = ((int)($valorForm['idcategoria_equipamento'] ?? 0) === (int)$cat['idcategoria_equipamento']) ? 'selected' : '';
                                                                            echo '<option value="' . (int)$cat['idcategoria_equipamento'] . '" ' . $sel . '>' . htmlspecialchars($cat['nome']) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Código</label>
                                                                <input type="text" class="form-control" name="codigo" value="<?= htmlspecialchars($valorForm['codigo'] ?? '') ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Património</label>
                                                                <input type="text" class="form-control" name="patrimonio" value="<?= htmlspecialchars($valorForm['patrimonio'] ?? '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label>Nome do Equipamento</label>
                                                                <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($valorForm['nome_equipamento'] ?? '') ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Departamento</label>
                                                                <input type="text" class="form-control" name="departamento" value="<?= htmlspecialchars($valorForm['departamento'] ?? '') ?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Localização</label>
                                                                <input type="text" class="form-control" name="localizacao" value="<?= htmlspecialchars($valorForm['localizacao'] ?? '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label>Data de Aquisição</label>
                                                                <input type="date" class="form-control" name="data_aquisicao" value="<?= htmlspecialchars($valorForm['data_aquisicao'] ?? '') ?>">
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label>Observações</label>
                                                                <input type="text" class="form-control" name="observacoes" value="<?= htmlspecialchars($valorForm['observacoes'] ?? '') ?>">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <input type="hidden" name="idequipamento" value="<?= $id ?>">
                                                    <input type="hidden" name="idveiculo" value="<?= $id ?>">
                                                    <button class="btn btn-warning" name="btnEditEquipamento"><i class="fas fa-save mr-1"></i>Guardar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Detalhes -->
                                <div class="modal fade" id="info<?= $id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title"><i class="fas fa-info-circle mr-2"></i>Detalhes do Equipamento</h5>
                                                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary">Identificação</h6>
                                                        <p><strong>Nº de Série:</strong> <?= htmlspecialchars($valorForm['numero_serie'] ?? $valorForm['matricula'] ?? '') ?></p>
                                                        <p><strong>IMEI:</strong> <?= htmlspecialchars($valorForm['imei'] ?? '—') ?></p>
                                                        <p><strong>Tipo:</strong> <?= htmlspecialchars($valorForm['tipo_equipamento'] ?? '—') ?></p>
                                                        <p><strong>Marca:</strong> <?= htmlspecialchars($valorForm['marca']) ?></p>
                                                        <p><strong>Modelo:</strong> <?= htmlspecialchars($valorForm['modelo']) ?></p>
                                                        <p><strong>Estado:</strong> <span class="badge badge-<?= $badgeColor ?>"><?= htmlspecialchars($valorForm['estado'] ?? '') ?></span></p>
                                                        <p><strong>Data de Entrada:</strong> <?= htmlspecialchars($valorForm['dataregisto'] ?? '—') ?></p>
                                                        <?php if (!empty($valorForm['categoria_equipamento']) || !empty($valorForm['codigo']) || !empty($valorForm['patrimonio'])): ?>
                                                        <h6 class="text-primary mt-3">Dados Patrimoniais</h6>
                                                        <p><strong>Categoria:</strong> <?= htmlspecialchars($valorForm['categoria_equipamento'] ?? '—') ?></p>
                                                        <p><strong>Código:</strong> <?= htmlspecialchars($valorForm['codigo'] ?? '—') ?></p>
                                                        <p><strong>Património:</strong> <?= htmlspecialchars($valorForm['patrimonio'] ?? '—') ?></p>
                                                        <p><strong>Departamento:</strong> <?= htmlspecialchars($valorForm['departamento'] ?? '—') ?></p>
                                                        <p><strong>Localização:</strong> <?= htmlspecialchars($valorForm['localizacao'] ?? '—') ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary">Cliente</h6>
                                                        <p><strong>Nome:</strong> <?= htmlspecialchars($valorForm['nome'] . ' ' . $valorForm['sobrenome']) ?></p>
                                                        <p><strong>NIF:</strong> <?= htmlspecialchars($valorForm['nif']) ?></p>
                                                        <p><strong>Telefone:</strong> <?= htmlspecialchars($valorForm['telefone'] ?? '—') ?></p>
                                                        <p><strong>Email:</strong> <?= htmlspecialchars($valorForm['email'] ?? '—') ?></p>
                                                    </div>
                                                </div>
                                                <?php if (!empty($valorForm['defeito_reportado'])): ?>
                                                <hr>
                                                <h6 class="text-primary">Defeito Reportado</h6>
                                                <p><?= nl2br(htmlspecialchars($valorForm['defeito_reportado'])) ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($valorForm['diagnostico_tecnico'])): ?>
                                                <h6 class="text-primary">Diagnóstico Técnico</h6>
                                                <p><?= nl2br(htmlspecialchars($valorForm['diagnostico_tecnico'])) ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($valorForm['garantia_reparacao'])): ?>
                                                <h6 class="text-primary">Garantia da Reparação</h6>
                                                <p><?= htmlspecialchars($valorForm['garantia_reparacao']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Fechar</button>
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

