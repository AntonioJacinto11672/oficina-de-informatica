<?php if (isset($this->dados)) {
    $valorForm = $this->dados;
}
?>
<div class="container-fluid">
    <div class="col-lg- mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">Alterar Foto Do Produto</h4>
            </div>
            <div class="card-body">
                <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                    <?php ?>
                    <div class="col-md-12 mb-3 text-center">
                        <img src="<?php echo URLADM . "app/adms/assets/foto/" . $valorForm['foto']; ?>" style="width: 25rem;height: 25rem;" class="px-3 px-sm-4 mt-3 mb-4 img-fluid prev-img img-thumbnail" id="preview-img" alt="Editar Foto de Perfil">
                        <br>

                        <div class="custom-file mb-3">
                            <input type="file" class="custom-file-input pb-4" id="foto" name="foto"  onchange="previewImagem();" required>
                            <label class="custom-file-label" for="foto" data-browse="Procurar foto">Escolha a Foto</label>
                            <div class="invalid-feedback">Escolha Uma foto de Perfil Nova</div>
                        </div>
                    </div>
                    <input type="hidden" name="idproduto" value="<?php echo $valorForm['idproduto'];?>"/>
                    <input class="col-lg-12 btn btn-primary acessar" type="submit"  name="btnEditFoto" value="Alterar" style="background-color: #1e4356;">

                </form>
            </div>
        </div>
    </div>
</div>
