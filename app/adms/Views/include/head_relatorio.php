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

        <!-- OverlayScrollbars -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/css/OverlayScrollbars.min.css" rel="stylesheet">

        <!-- Tempusdominus Bootstrap 4 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">

        <!-- iCheck Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css" rel="stylesheet">

        <!-- Daterange picker -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css" rel="stylesheet">

        <!-- Summernote -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">

        <!-- DataTables -->
        <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css" rel="stylesheet">

        <!-- BS Stepper -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bs-stepper/1.7.0/css/bs-stepper.min.css" rel="stylesheet">

        <!-- AdminLTE dist CSS (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/dist/css/adminlte.min.css" rel="stylesheet">

        <!-- Local CSS -->
        <link href="<?php echo URLADM; ?>app/adms/assets/css/style.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/form-validation.css" rel="stylesheet">
    </head>
    <body class="hold-transition layout-top-nav">
