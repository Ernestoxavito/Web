<?php

include 'conexao.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        $sql = "SELECT id FROM Cadastro WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Gera um token único
            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Salva o token e expiração no banco (crie os campos se não existirem)
            $update = $conexao->prepare("UPDATE Cadastro SET reset_token=?, reset_expira=? WHERE id=?");
            $update->bind_param("ssi", $token, $expira, $user_id);
            $update->execute();

            // Monta o link de redefinição
            $link = "http://localhost/projeto/redefinir_senha.php?token=$token";

            // Envia o e-mail (usando PHPMailer)
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'delsonafredo09@gmail.com';
                $mail->Password   = 'txwr mclm vcyx izcf';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('SEU_EMAIL@gmail.com', 'Recuperação de Senha');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $assunto = "Recuperação de senha";
                $mail->Subject = $assunto;
                $mail->Body    = "Clique no link para redefinir sua senha: <a href='$link'>$link</a>";
                $mail->AltBody = "Clique no link para redefinir sua senha: $link";

                $mail->send();
                $mensagem = "Um link de redefinição foi enviado para seu e-mail.";
            } catch (Exception $e) {
                $mensagem = "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
            }
        } else {
            $mensagem = "E-mail não encontrado.";
        }
        $stmt->close();
    } else {
        $mensagem = "Informe seu e-mail.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../img/icon/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Rubik:wght@300;400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="bootstrap-grid.min.css" />
    <link rel="stylesheet" href="src/assets/css/loginStyle.css" />  
</head>
<body>
    <div class="container-form">
        <h2>Recuperar Senha</h2>
        <?php if (!empty($mensagem)) : ?>
            <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Digite seu e-mail" required />
            <button type="submit">Enviar</button>
        </form>
        <a href="login.php">Voltar ao login</a>
    </div>
</body>
</html>