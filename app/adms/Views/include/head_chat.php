<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Sistema de gestão de Oficina Mecânica</title>
        <meta content="#Sistema de gestão de Oficina Mecânica" name="description">
        <meta content="Oficina,Mecânica,Carros,Motas, Reparação" name="keywords">

        <!-- Favicons -->
        <link href="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo.png" rel="icon">

        <!-- Font Awesome 5 Free -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

        <!-- Bootstrap 4 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Icon Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/icofont/dist/icofont.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" rel="stylesheet">

        <!-- Animate.css -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

        <!-- OWL Carousel -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- AOS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

        <!-- W3.css -->
        <link href="https://www.w3schools.com/lib/w3.css" rel="stylesheet">

        <!-- DataTables -->
        <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">

        <!-- Local CSS -->
        <link href="<?php echo URLADM; ?>app/adms/assets/css/adminlte.min.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/form-validation.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/personalizado1.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/chat.css" rel="stylesheet">
    </head>
    <body class="page-top">
        <!--  Modal Editar Perfil-->
        <div class="modal fade" id="editperfil<?php echo $_SESSION['idlogado']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="<?php echo URLADM; ?>perfil" method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Editar?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" placeholder="Digete o Nome" name="nome" value="<?php
                                    if (isset($_SESSION['nome'])) {
                                        echo $_SESSION['nome'];
                                    }
                                    ?>" required>
                                    <div class="invalid-feedback">Insira O Nome.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sobrenome">Sobrenome</label>
                                    <input type="text" class="form-control" id="sobrenome" placeholder="Inseri O Sobrenome" name="sobrenome" value="<?php
                                    if (isset($_SESSION['sobrenome'])) {
                                        echo $_SESSION['sobrenome'];
                                    }
                                    ?>" required>
                                    <div class="invalid-feedback">Campo Obrigatório.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Nº Do Bilhete</label>
                                    <input type="text" class="form-control" placeholder="Digete O Número do Bilhete" name="nbi" value="<?php
                                    if (isset($_SESSION['nbi'])) {
                                        echo $_SESSION['nbi'];
                                    }
                                    ?>" required>
                                    <div class="invalid-feedback">Campo Obrigatório.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nif">Nº de Identificação Fiscal (NIF)</label>
                                    <input type="text" class="form-control" id="nif" placeholder="Nº de Identificação Fiscal (NIF)" name="nif" value="<?php
                                    if (isset($_SESSION['nif'])) {
                                        echo $_SESSION['nif'];
                                    }
                                    ?>" required>
                                    <div class="invalid-feedback">Campo Obrigatório.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="emailnovo">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="you@example.com" name="emailnovo" value="<?php
                                if (isset($_SESSION['email'])) {
                                    echo $_SESSION['email'];
                                }
                                ?>" required>
                                <div class="invalid-feedback">Campo Obrigatório.</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Nº de Telefone</label>
                                    <input type="text" class="form-control" placeholder="Número do Telefone" name="telefone" value="<?php
                                    if (isset($_SESSION['telefone'])) {
                                        echo $_SESSION['telefone'];
                                    }
                                    ?>" required>
                                    <div class="invalid-feedback">Campo Obrigatório.</div>
                                </div>
                                <?php if ($_SESSION['usuario'] != "adimin") { ?>
                                    <div class="col-md-6 mb-3">
                                        <label>Morada</label>
                                        <input type="text" class="form-control" placeholder="Digete A Morada" name="morada" value="<?php
                                        if (isset($_SESSION['morada'])) {
                                            echo $_SESSION['morada'];
                                        }
                                        ?>" required>
                                        <div class="invalid-feedback">Campo Obrigatório.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <input type="hidden" name="idusuario" value="<?php echo $_SESSION['idlogado']; ?>">
                            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
                            <input type="hidden" name="nbiantigo" value="<?php echo $_SESSION['nbi']; ?>">
                            <input type="hidden" name="nifantigo" value="<?php echo $_SESSION['nif']; ?>">
                            <input type="hidden" name="nivel" value="<?php echo $_SESSION['usuario']; ?>">
                            <button class="btn btn-primary" name="btnEditPerfil">Sim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
