<?php
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'delsonafredo09@gmail.com'; // Seu Gmail
    $mail->Password   = 'utwo mrcm qybu eoog'; // Senha de app do Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('delsonafredo09@gmail.com', 'Delson da Cruz');
    $mail->addAddress('destinatario@exemplo.com', 'Destinatário');

    $mail->isHTML(true);
    $mail->Subject = 'Assunto do E-mail';
    $mail->Body    = 'Conteúdo <b>HTML</b> do e-mail';
    $mail->AltBody = 'Conteúdo texto do e-mail';

    $mail->send();
    echo 'E-mail enviado com sucesso!';
} catch (Exception $e) {
    echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
}