<?php

namespace App\adms\Models;

use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

class AdmsRecuperarSenha extends Conn {

    private $conn;

    public function verificarEmail(string $email): bool {
        $this->conn = $this->connect();
        $stmt = $this->conn->prepare("SELECT idusuario FROM usuario WHERE email = :email AND st_conta = 'Ativada' LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return (bool) $stmt->fetch();
    }

    public function gerarEnviarCodigo(string $email): bool {
        $this->conn = $this->connect();
        $codigo  = (string) rand(100000, 999999);
        $expira  = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Invalidar códigos anteriores não usados deste email
        $stmt = $this->conn->prepare("UPDATE reset_senha SET usado = 1 WHERE email = :email AND usado = 0");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Inserir novo código
        $stmt = $this->conn->prepare("INSERT INTO reset_senha (email, codigo, expira_em) VALUES (:email, :codigo, :expira)");
        $stmt->bindParam(':email',  $email,  PDO::PARAM_STR);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->bindParam(':expira', $expira, PDO::PARAM_STR);
        $stmt->execute();

        if (!$stmt->rowCount()) {
            return false;
        }

        return $this->enviarEmail($email, $codigo);
    }

    private function enviarEmail(string $email, string $codigo): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = \Core\Config::get('SMTP_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = \Core\Config::get('SMTP_USER');
            $mail->Password   = \Core\Config::get('SMTP_PASS');
            $mail->SMTPSecure = \Core\Config::get('SMTP_SECURE', 'tls');
            $mail->Port       = (int) \Core\Config::get('SMTP_PORT', 587);
            $mail->CharSet    = 'UTF-8';

            $nomeSistema = \Core\Config::get('APP_NAME', 'Oficina Mecânica');

            $mail->setFrom(\Core\Config::get('OFFICE_EMAIL'), $nomeSistema);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha — ' . $nomeSistema;
            $mail->Body    = $this->templateEmail($codigo, $nomeSistema);

            $mail->send();
            return true;
        } catch (Exception $e) {
            $_SESSION['msg'] = '<div class="alert alert-danger text-center">Erro ao enviar email. Verifique as configurações SMTP.<br><small>' . htmlspecialchars($mail->ErrorInfo) . '</small></div>';
            return false;
        }
    }

    private function templateEmail(string $codigo, string $nomeSistema): string {
        $ano = date('Y');
        return "
        <div style='font-family:Arial,sans-serif;max-width:520px;margin:auto;padding:30px;border:1px solid #ddd;border-radius:10px;'>
            <h2 style='color:#1e4356;text-align:center;margin-bottom:5px;'>{$nomeSistema}</h2>
            <h3 style='text-align:center;color:#444;'>Recuperação de Senha</h3>
            <hr style='border:none;border-top:1px solid #eee;margin:15px 0;'>
            <p style='color:#555;'>Recebemos um pedido de recuperação de senha para a sua conta.</p>
            <p style='color:#555;'>O seu código de verificação é:</p>
            <div style='font-size:40px;font-weight:bold;letter-spacing:10px;text-align:center;color:#1e4356;
                        background:#f0f4f8;padding:25px;border-radius:8px;margin:20px 0;'>
                {$codigo}
            </div>
            <p style='color:#888;font-size:14px;'>Este código é válido por <strong>30 minutos</strong>.</p>
            <p style='color:#aaa;font-size:12px;'>Se não solicitou a recuperação de senha, ignore este email.</p>
            <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
            <p style='color:#bbb;font-size:11px;text-align:center;'>{$nomeSistema} &copy; {$ano}</p>
        </div>";
    }

    public function verificarCodigo(string $email, string $codigo): bool {
        $this->conn = $this->connect();
        $agora = date('Y-m-d H:i:s');
        $stmt  = $this->conn->prepare(
            "SELECT id FROM reset_senha
             WHERE email = :email AND codigo = :codigo AND usado = 0 AND expira_em > :agora
             LIMIT 1"
        );
        $stmt->bindParam(':email',  $email,  PDO::PARAM_STR);
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->bindParam(':agora',  $agora,  PDO::PARAM_STR);
        $stmt->execute();
        return (bool) $stmt->fetch();
    }

    public function alterarSenha(string $email, string $novaSenha): bool {
        $this->conn = $this->connect();
        $hash = md5($novaSenha);

        $stmt = $this->conn->prepare("UPDATE usuario SET senha = :senha, modified = NOW() WHERE email = :email");
        $stmt->bindParam(':senha', $hash,  PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $stmt2 = $this->conn->prepare("UPDATE reset_senha SET usado = 1 WHERE email = :email");
            $stmt2->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt2->execute();
            return true;
        }
        return false;
    }
}
