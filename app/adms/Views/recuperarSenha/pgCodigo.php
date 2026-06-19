<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
$emailMascarado = '';
if (isset($_SESSION['reset_email'])) {
    $partes = explode('@', $_SESSION['reset_email']);
    $local  = $partes[0];
    $dominio = $partes[1] ?? '';
    $emailMascarado = substr($local, 0, 2) . str_repeat('*', max(strlen($local) - 2, 2)) . '@' . $dominio;
}
?>

<div class="form-signin shadow" style="text-align:center;">
    <img class="mb-4" src="<?php echo URLADM; ?>app/adms/assets/imagens/login/logo.png" alt="" width="75%" height="75">

    <h5 class="mb-2 font-weight-bold" style="color:#1e4356;">Verificar Código</h5>
    <p class="text-muted mb-4" style="font-size:14px;">
        Enviámos um código de 6 dígitos para<br>
        <strong><?php echo htmlspecialchars($emailMascarado); ?></strong><br>
        <span style="font-size:12px;color:#aaa;">Válido por 30 minutos.</span>
    </p>

    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="" class="needs-validation" novalidate>
        <div class="d-flex justify-content-center mb-3" style="gap:8px;">
            <?php for ($i = 1; $i <= 6; $i++): ?>
            <input type="text" maxlength="1" class="form-control codigo-digit text-center font-weight-bold"
                   style="width:48px;height:56px;font-size:24px;border:2px solid #ccc;border-radius:8px;"
                   data-index="<?php echo $i; ?>" <?php echo $i === 1 ? 'autofocus' : ''; ?>>
            <?php endfor; ?>
        </div>
        <input type="hidden" name="codigo" id="codigoHidden">

        <button class="col-lg-12 btn btn-primary mb-2" type="submit" name="btnVerificarCodigo"
                id="btnVerificar" style="background-color:#1e4356;border-color:#1e4356;">
            Verificar Código
        </button>
    </form>

    <form method="POST" action="" class="mt-2">
        <button type="submit" name="btnReenviarCodigo" class="btn btn-link" style="font-size:13px;color:#1e4356;">
            Reenviar código
        </button>
        <span style="color:#ccc;">|</span>
        <button type="submit" name="btnCancelar" class="btn btn-link" style="font-size:13px;color:#999;">
            Cancelar
        </button>
    </form>
</div>

<script>
(function () {
    var digits = document.querySelectorAll('.codigo-digit');
    var hidden = document.getElementById('codigoHidden');
    var btn    = document.getElementById('btnVerificar');

    digits.forEach(function (input, idx) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(-1);
            if (this.value && idx < digits.length - 1) {
                digits[idx + 1].focus();
            }
            actualizarHidden();
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                digits[idx - 1].focus();
            }
        });
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            var texto = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            texto.split('').forEach(function (c, i) {
                if (digits[i]) digits[i].value = c;
            });
            if (digits[Math.min(texto.length, 5)]) digits[Math.min(texto.length, 5)].focus();
            actualizarHidden();
        });
    });

    function actualizarHidden() {
        var codigo = '';
        digits.forEach(function (d) { codigo += d.value; });
        hidden.value = codigo;
        btn.disabled = codigo.length !== 6;
    }

    btn.disabled = true;
})();
</script>
