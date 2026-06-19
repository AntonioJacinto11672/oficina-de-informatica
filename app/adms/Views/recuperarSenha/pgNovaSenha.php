<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>

<form class="form-signin shadow needs-validation" novalidate method="POST" action="">
    <img class="mb-4" src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo.png" alt="" width="75%" height="75">

    <h5 class="mb-2 font-weight-bold text-center" style="color:#1e4356;">Nova Senha</h5>
    <p class="text-muted text-center mb-4" style="font-size:14px;">
        Crie uma nova senha para a sua conta.
    </p>

    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <div class="input-group mb-3">
        <input type="password" id="nova_senha" name="nova_senha" class="form-control"
               placeholder="Nova senha (mín. 6 caracteres)" minlength="6" required>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha('nova_senha', this)"
                    tabindex="-1" title="Mostrar/ocultar senha">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback">A senha deve ter no mínimo 6 caracteres.</div>
    </div>

    <div class="input-group mb-3">
        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control"
               placeholder="Repita a nova senha" minlength="6" required>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha('confirmar_senha', this)"
                    tabindex="-1" title="Mostrar/ocultar senha">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback" id="feedbackConfirmar">As senhas não coincidem.</div>
    </div>

    <button class="col-lg-12 btn btn-success mb-2" type="submit" name="btnAlterarSenha" id="btnAlterar"
            style="background-color:#1e4356;border-color:#1e4356;">
        Alterar Senha
    </button>

    <div class="mt-2 text-center">
        <form method="POST" action="" style="display:inline;">
            <button type="submit" name="btnCancelar" class="btn btn-link" style="color:#999;font-size:13px;">
                Cancelar
            </button>
        </form>
    </div>
</form>

<script>
function toggleSenha(id, btn) {
    var input = document.getElementById(id);
    var icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.getElementById('confirmar_senha').addEventListener('input', function () {
    var nova      = document.getElementById('nova_senha').value;
    var confirmar = this.value;
    var feedback  = document.getElementById('feedbackConfirmar');
    if (confirmar && confirmar !== nova) {
        this.setCustomValidity('As senhas não coincidem.');
        feedback.textContent = 'As senhas não coincidem.';
    } else {
        this.setCustomValidity('');
        feedback.textContent = 'As senhas não coincidem.';
    }
});
</script>
