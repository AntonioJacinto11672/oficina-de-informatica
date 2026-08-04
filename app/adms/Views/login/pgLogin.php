<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
//$md5 = md5(123456789);
//$round = rand(1000, 999999);
//$uniqiue = uniqid();
//$uniqiue_rand = uniqid().rand(1000, 999999);
//echo $md5;
//echo "<br>".$round;
//echo "<br>".$uniqiue;
//echo "<br>".$uniqiue_rand;



?>


<form class="form-signin shadow needs-validation" novalidate method="POST" action="">
    <img class="mb-4" src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo_novo.png" alt="" width="75%" height="75">
    <!--<h1 class="h3 mb-3 font-weight-normal">Login</h1>-->
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    if (isset($this->dados['form'])) {
        $valorForm = $this->dados['form'];
        //var_dump($valorForm);
    }
    ?>
    <label for="email" class="sr-only">E-mail</label>
    <input type="email" id="email" name="btnUsuario" class="form-control" placeholder="Introduza o seu e-mail..." value="<?php
    if (isset($valorForm['btnUsuario'])) {
        echo htmlspecialchars($valorForm['btnUsuario']);
    }
    ?>" required autofocus>
    <div class="invalid-feedback">
        Introduza um e-mail válido.
    </div>
    <br>
    <label for="btnPassword" class="sr-only">Senha</label>
    <input type="password" id="btnPassword" class="form-control" name="btnPassword" placeholder="Introduza a sua senha..." required>
    <div class="invalid-feedback">
        A senha é um campo obrigatório.
    </div>
    <!--<input class="btn btn-lg btn-primary btn-block" type="submit" name="btnEntrar" value="Acessar">-->
    <input class=" col-lg-12 btn btn-primary acessar" type="submit"  name="btnEntrar" value="Acessar" style="background-color: #1e4356;">
    <p class="mt-3 mb-0">
        <a href="<?php echo URLADM; ?>recuperarSenha" style="color:#1e4356;font-size:14px;">Esqueci a minha senha</a>
    </p>
</form>
