<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>

<form class="form-signin shadow needs-validation" novalidate method="POST" action="">
    <img class="mb-4" src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo.png" alt="" width="75%" height="75">

    <h5 class="mb-3 font-weight-bold text-center" style="color:#1e4356;">Recuperar Senha</h5>
    <p class="text-muted text-center mb-4" style="font-size:14px;">
        Introduza o seu email. Enviaremos um código de 6 dígitos para recuperar a senha.
    </p>

    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <label for="email" class="sr-only">Email</label>
    <input type="email" id="email" name="email" class="form-control mb-3"
           placeholder="Endereço de email..." required autofocus>
    <div class="invalid-feedback">
        Insira um endereço de email válido.
    </div>

    <button class="col-lg-12 btn btn-primary" type="submit" name="btnEnviarCodigo"
            style="background-color:#1e4356;border-color:#1e4356;">
        Enviar Código
    </button>

    <div class="mt-3 text-center">
        <form method="POST" action="" style="display:inline;">
            <button type="submit" name="btnCancelar" class="btn btn-link" style="color:#1e4356;font-size:14px;">
                &larr; Voltar ao Login
            </button>
        </form>
    </div>
</form>
