<?php

namespace App\adms\Models;

use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Técnicos de Informática — ficha do técnico (tabela `tecnicos`) e a conta
 * de acesso ao sistema associada (tabela `usuario`, nivel='tecnico').
 */
class AdmsTecnico extends Conn {

    private $dados;
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
    }

    protected function limparInput($input) {
        return htmlspecialchars(strip_tags(trim((string)$input)));
    }

    private function upload(): bool {
        if (empty($this->dados['foto']['name']) || empty($this->dados['foto']['tmp_name']) || !is_uploaded_file($this->dados['foto']['tmp_name'])) {
            $this->dados['novo_nome'] = null;
            return true;
        }
        $formatos = ['png', 'jpg', 'jpeg'];
        $mimesPermitidos = ["image/png", "image/jpeg"];
        $extensao = strtolower(pathinfo($this->dados['foto']['name'], PATHINFO_EXTENSION));
        $mimeReal = function_exists('finfo_open') ? (new \finfo(FILEINFO_MIME_TYPE))->file($this->dados['foto']['tmp_name']) : null;

        if (!in_array($extensao, $formatos, true) || ($mimeReal !== null && !in_array($mimeReal, $mimesPermitidos, true))) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Ficheiro não permitido. Escolha uma imagem (png, jpg, jpeg).</div>';
            return false;
        }
        if ($this->dados['foto']['size'] > 5 * 1024 * 1024) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">A imagem não pode exceder 5 MB.</div>';
            return false;
        }
        $pasta = "app/adms/assets/foto/";
        $this->dados['novo_nome'] = uniqid() . "." . $extensao;
        if (move_uploaded_file($this->dados['foto']['tmp_name'], $pasta . $this->dados['novo_nome'])) {
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível carregar o ficheiro.</div>';
        return false;
    }

    public function dadosTecnico(): array {
        $query = "SELECT t.*, u.st_conta, u.idusuario FROM tecnicos t LEFT JOIN usuario u ON u.nbi = t.nbi AND u.nif = t.nif AND u.nivel = 'tecnico' ORDER BY t.nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cdsTecnico($dados) {
        $this->dados = $dados;
        $this->dados['email'] = $this->limparInput($this->dados['email'] ?? '');
        $this->dados['nome'] = $this->limparInput($this->dados['nome'] ?? '');
        $this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome'] ?? '');
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi'] ?? '');
        $this->dados['nif'] = $this->limparInput($this->dados['nif'] ?? '');
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone'] ?? '');
        $this->dados['morada'] = $this->limparInput($this->dados['morada'] ?? '');

        if (!$this->valTecnicos() || !$this->upload()) {
            return false;
        }

        $query = "INSERT INTO tecnicos(nbi, nif, nome, sobrenome, email, telefone, morada, created, foto) VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :morada, NOW(), :foto)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nbi", $this->dados['nbi']);
        $stmt->bindParam(":nif", $this->dados['nif']);
        $stmt->bindParam(":nome", $this->dados['nome']);
        $stmt->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmt->bindParam(":email", $this->dados['email']);
        $stmt->bindParam(":telefone", $this->dados['telefone']);
        $stmt->bindParam(":morada", $this->dados['morada']);
        $stmt->bindParam(":foto", $this->dados['novo_nome']);
        $stmt->execute();

        if (!$stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
            return false;
        }

        $senhaTemporaria = (string)random_int(100000, 999999);
        $senhaHash = password_hash($senhaTemporaria, PASSWORD_DEFAULT);
        $nivel = 'tecnico';
        $stConta = 'Ativada';

        $queryLogin = "INSERT INTO usuario (nbi, nif, nome, sobrenome, email, telefone, senha, nivel, st_conta, foto, created)
                        VALUES (:nbi, :nif, :nome, :sobrenome, :email, :telefone, :senha, :nivel, :st_conta, :foto, NOW())";
        $stmtLogin = $this->conn->prepare($queryLogin);
        $stmtLogin->bindParam(":nome", $this->dados['nome']);
        $stmtLogin->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmtLogin->bindParam(":email", $this->dados['email']);
        $stmtLogin->bindParam(":telefone", $this->dados['telefone']);
        $stmtLogin->bindParam(":senha", $senhaHash);
        $stmtLogin->bindParam(":nivel", $nivel);
        $stmtLogin->bindParam(":st_conta", $stConta);
        $stmtLogin->bindParam(":foto", $this->dados['novo_nome']);
        $stmtLogin->bindParam(":nbi", $this->dados['nbi']);
        $stmtLogin->bindParam(":nif", $this->dados['nif']);
        $stmtLogin->execute();

        if (!$stmtLogin->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Técnico registado, mas não foi possível criar a conta de acesso.</div>';
            return false;
        }

        if ($this->enviarCredenciais($this->dados['email'], $this->dados['nome'], $senhaTemporaria)) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Técnico registado com sucesso. As credenciais de acesso foram enviadas por e-mail.</div>';
        } else {
            $_SESSION['msg'] = '<div class="alert alert-warning text-center">Técnico registado com sucesso, mas o e-mail com as credenciais não foi enviado. Verifique as configurações SMTP em <code>.env</code> ou comunique a senha temporária ao técnico por outro meio: <strong>' . htmlspecialchars($senhaTemporaria) . '</strong>.</div>';
        }
        return true;
    }

    /**
     * Bilhete de Identidade: 9 dígitos + 2 letras + 3 dígitos (14 caracteres,
     * ex: 123456789LA123). Telefone: 9 dígitos, começando por 9 (rede móvel).
     */
    private function formatosValidos(): bool {
        if (!preg_match('/^\d{9}[A-Za-z]{2}\d{3}$/', $this->dados['nbi'])) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Número de BI inválido — deve ter 14 caracteres: 9 números, 2 letras e 3 números (ex: 123456789LA123).</div>';
            return false;
        }
        if (!preg_match('/^9\d{8}$/', $this->dados['telefone'])) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Número de telefone inválido — deve ter 9 dígitos e começar por 9.</div>';
            return false;
        }
        return true;
    }

    private function valTecnicos(): bool {
        if (!$this->formatosValidos()) {
            return false;
        }
        $stmt = $this->conn->prepare("SELECT nbi FROM usuario WHERE nbi = :nbi");
        $stmt->bindParam(':nbi', $this->dados['nbi']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este número de BI.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT email FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $this->dados['email']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este e-mail.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT nif FROM usuario WHERE nif = :nif");
        $stmt->bindParam(':nif', $this->dados['nif']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este NIF.</div>';
            return false;
        }

        return true;
    }

    private function valEditTecnicos(): bool {
        if (!$this->formatosValidos()) {
            return false;
        }
        $this->idUsuario();
        $stmt = $this->conn->prepare("SELECT nbi FROM usuario WHERE nbi = :nbi AND idusuario <> :idusuario");
        $stmt->bindParam(':nbi', $this->dados['nbi']);
        $stmt->bindParam(':idusuario', $this->dados['idusuario']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este número de BI.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT email FROM usuario WHERE email = :email AND idusuario <> :idusuario");
        $stmt->bindParam(':email', $this->dados['emailnovo']);
        $stmt->bindParam(':idusuario', $this->dados['idusuario']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este e-mail.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT nif FROM usuario WHERE nif = :nif AND idusuario <> :idusuario");
        $stmt->bindParam(':nif', $this->dados['nif']);
        $stmt->bindParam(':idusuario', $this->dados['idusuario']);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Já existe um utilizador registado com este NIF.</div>';
            return false;
        }

        return true;
    }

    public function idUsuario(): void {
        $stmt = $this->conn->prepare("SELECT idusuario FROM usuario WHERE email=:email AND nif=:nif AND nbi=:nbi LIMIT 1");
        $stmt->bindParam(":email", $this->dados['email']);
        $stmt->bindParam(":nif", $this->dados['nif']);
        $stmt->bindParam(":nbi", $this->dados['nbi']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->dados['idusuario'] = $row['idusuario'] ?? 0;
    }

    public function deleteTecnico($dados): bool {
        $this->dados = $dados;
        $this->dados['idtecnico'] = (int)$this->limparInput($this->dados['idtecnico']);
        $this->idUsuario();

        $stmt = $this->conn->prepare("DELETE FROM tecnicos WHERE idtecnico=:idtecnico");
        $stmt->bindParam(":idtecnico", $this->dados['idtecnico'], PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível eliminar o técnico.</div>';
            return false;
        }

        $stmtUser = $this->conn->prepare("DELETE FROM usuario WHERE idusuario=:idusuario");
        $stmtUser->bindParam(":idusuario", $this->dados['idusuario'], PDO::PARAM_INT);
        $stmtUser->execute();

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Técnico eliminado com sucesso.</div>';
        return true;
    }

    public function editTecnico($dados): bool {
        $this->dados = $dados;
        $this->idUsuario();
        $this->dados['emailnovo'] = $this->limparInput($this->dados['emailnovo'] ?? '');
        $this->dados['nome'] = $this->limparInput($this->dados['nome'] ?? '');
        $this->dados['sobrenome'] = $this->limparInput($this->dados['sobrenome'] ?? '');
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi'] ?? '');
        $this->dados['nif'] = $this->limparInput($this->dados['nif'] ?? '');
        $this->dados['telefone'] = $this->limparInput($this->dados['telefone'] ?? '');
        $this->dados['morada'] = $this->limparInput($this->dados['morada'] ?? '');

        if (!$this->valEditTecnicos()) {
            return false;
        }

        $query = "UPDATE tecnicos SET nbi=:nbi, nif=:nif, nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, morada=:morada WHERE idtecnico=:idtecnico";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nbi", $this->dados['nbi']);
        $stmt->bindParam(":nif", $this->dados['nif']);
        $stmt->bindParam(":nome", $this->dados['nome']);
        $stmt->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmt->bindParam(":email", $this->dados['emailnovo']);
        $stmt->bindParam(":telefone", $this->dados['telefone']);
        $stmt->bindParam(":morada", $this->dados['morada']);
        $stmt->bindParam(":idtecnico", $this->dados['idtecnico']);
        $stmt->execute();

        if (!$stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível guardar os dados.</div>';
            return false;
        }

        // A palavra-passe NÃO é alterada aqui — só pela recuperação de senha.
        $queryLogin = "UPDATE usuario SET nome=:nome, sobrenome=:sobrenome, email=:email, telefone=:telefone, nbi=:nbi, nif=:nif, modified=NOW() WHERE idusuario=:idusuario";
        $stmtLogin = $this->conn->prepare($queryLogin);
        $stmtLogin->bindParam(":nome", $this->dados['nome']);
        $stmtLogin->bindParam(":sobrenome", $this->dados['sobrenome']);
        $stmtLogin->bindParam(":email", $this->dados['emailnovo']);
        $stmtLogin->bindParam(":telefone", $this->dados['telefone']);
        $stmtLogin->bindParam(":nbi", $this->dados['nbi']);
        $stmtLogin->bindParam(":nif", $this->dados['nif']);
        $stmtLogin->bindParam(":idusuario", $this->dados['idusuario']);
        $stmtLogin->execute();

        $_SESSION['msg'] = '<div class="alert alert-success text-center">Dados atualizados com sucesso.</div>';
        return true;
    }

    public function ativarConta($dados): bool {
        $this->dados = $dados;
        $this->dados['nbi'] = $this->limparInput($this->dados['nbi'] ?? '');
        $this->dados['nif'] = $this->limparInput($this->dados['nif'] ?? '');
        $this->dados['email'] = $this->limparInput($this->dados['email'] ?? '');
        $estadoAtual = $this->limparInput($this->dados['st_conta'] ?? '');
        $novoEstado = $estadoAtual === 'Ativada' ? 'Desativada' : 'Ativada';

        $this->idUsuario();
        if (empty($this->dados['idusuario'])) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Técnico não encontrado.</div>';
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE usuario SET st_conta=:st_conta, modified=NOW() WHERE idusuario=:idusuario");
        $stmt->bindParam(":st_conta", $novoEstado);
        $stmt->bindParam(":idusuario", $this->dados['idusuario'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = '<div class="alert alert-success text-center">Estado da conta atualizado com sucesso.</div>';
            return true;
        }
        $_SESSION['msg'] = '<div class="alert alert-danger text-center">Não foi possível atualizar o estado da conta.</div>';
        return false;
    }

    private function enviarCredenciais(string $email, string $nome, string $senhaTemporaria): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = \Core\Config::get('SMTP_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth = true;
            $mail->Username = \Core\Config::get('SMTP_USER');
            $mail->Password = \Core\Config::get('SMTP_PASS');
            $mail->SMTPSecure = \Core\Config::get('SMTP_SECURE', 'tls');
            $mail->Port = (int) \Core\Config::get('SMTP_PORT', 587);
            $mail->CharSet = 'UTF-8';

            $nomeSistema = \Core\Config::get('APP_NAME', 'Sistema de Gestão de Manutenção Preventiva e Corretiva de Equipamentos Informáticos da Universidade Lusíada de Angola');
            $mail->setFrom(\Core\Config::get('IT_DEPT_EMAIL', 'geral@ula.co.ao'), $nomeSistema);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'As suas credenciais de acesso — ' . $nomeSistema;
            $mail->Body = "
                <div style='font-family:Arial,sans-serif;max-width:520px;margin:auto;padding:30px;border:1px solid #ddd;border-radius:10px;'>
                    <h2 style='color:#1e4356;text-align:center;'>{$nomeSistema}</h2>
                    <p>Olá {$nome},</p>
                    <p>Foi registada uma conta de Técnico para si no sistema. Pode aceder com os seguintes dados:</p>
                    <p><strong>E-mail:</strong> {$email}<br><strong>Palavra-passe temporária:</strong> {$senhaTemporaria}</p>
                    <p style='color:#888;font-size:13px;'>Por segurança, altere esta palavra-passe assim que possível através da opção 'Esqueci-me da Senha'.</p>
                </div>";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Falha ao enviar credenciais por e-mail para ' . $email . ': ' . $mail->ErrorInfo);
            return false;
        }
    }
}
