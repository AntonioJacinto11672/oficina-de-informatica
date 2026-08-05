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
        <link href="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" rel="icon">
        <title>Sistema de gestão de Assistência técnica</title>

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

        <!-- W3.css (local) -->
        <link href="<?php echo URLADM; ?>app/adms/assets/vendor/w3/w3.css" rel="stylesheet">

        <!-- Local CSS -->
        <link href="<?php echo URLADM; ?>app/adms/assets/css/style.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/personalizado1.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/docs.min.css" rel="stylesheet">
        <link href="<?php echo URLADM; ?>app/adms/assets/css/form-validation.css" rel="stylesheet">
    </head>
    <body>
