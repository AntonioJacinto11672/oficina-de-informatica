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
        <title>Sistema de Gestão de Manutenção de Equipamentos Informáticos — Universidade Lusíada de Angola</title>
        <meta content="Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos da Universidade Lusíada de Angola" name="description">
        <meta content="manutenção, equipamentos informáticos, TI, Universidade Lusíada de Angola" name="keywords">

        <!-- Favicons -->
        <link href="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" rel="icon">

        <!-- Font Awesome 5 Free (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/font-awesome/css/all.min.css" rel="stylesheet">

        <!-- Bootstrap 4 (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Icon Fonts (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/icofont/css/icofont.min.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

        <!-- Animate.css (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/animate/animate.min.css" rel="stylesheet">

        <!-- OWL Carousel (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/owlcarousel/css/owl.carousel.min.css" rel="stylesheet">

        <!-- AOS (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/aos/aos.css" rel="stylesheet">

        <!-- OverlayScrollbars (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/overlayscrollbars/css/OverlayScrollbars.min.css" rel="stylesheet">

        <!-- Tempusdominus Bootstrap 4 (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">

        <!-- iCheck Bootstrap (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css" rel="stylesheet">

        <!-- Daterange picker (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/daterangepicker/daterangepicker.css" rel="stylesheet">

        <!-- Summernote (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/summernote/summernote-bs4.min.css" rel="stylesheet">

        <!-- DataTables (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-responsive/css/responsive.bootstrap4.min.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-buttons/css/buttons.bootstrap4.min.css" rel="stylesheet">

        <!-- BS Stepper (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/plugins/bs-stepper/css/bs-stepper.min.css" rel="stylesheet">

        <!-- AdminLTE dist CSS (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/dist/css/adminlte.min.css" rel="stylesheet">

        <!-- Local CSS -->
        <link href="<?php echo URLADM; ?>app/adms/assets/css/style.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/form-validation.css" rel="stylesheet">
    </head>
    <body class="hold-transition layout-top-nav">
