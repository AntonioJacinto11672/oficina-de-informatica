<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>
<!-- ======= Top Bar ======= -->
<section id="topbar" class=" d-lg-block">
    <div class="container clearfix">
        <div class="contact-info float-right">
            <i class="icofont-user"></i><a href="#"><?php if (isset($_SESSION['logado'])) { ?><?php echo $_SESSION['usuario'] . " : " . $_SESSION['nome'] . " " . $_SESSION['sobrenome']; ?> </a>
                <?php
            } else {
                echo "RESOLVE ESSE BAQUE";
            }
            ?>
        </div>
    </div>
</section>

<!-- ======= Header ======= -->
<header id="header">
    <div class="container">

        <nav class="nav justify-content-center navbar-light sticky-top">
            <div class="logo float-right col-md-6  col-lg-2 mr-0 px-3">
                <h1 class="text-light"><a class="navbar-brand bg-light btn btn-outline-secondary acessar" href=""><span>Sistema de Gestão de Assistência Técnica Informática</span></a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.ht
                ml"><img src="Views/Administrador/assets/img/logo_novo.png" alt="" class="img-fluid"></a>-->
            </div>
            <button class="navbar-toggler position-absolute d-md-none collapsed float-center" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>
</header><!-- End Header --><!-- .nav-menu -->

