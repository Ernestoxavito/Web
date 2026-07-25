<?php

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$nome = $_POST['Nome'] ?? '';
$email = $_POST['Email'] ?? '';
$telefone = $_POST['Telefone'] ?? '';
$mensagem = $_POST['msg'] ?? '';

if (!$nome || !$email || !$telefone) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'delsonafredo09@gmail.com'; // Seu Gmail
    $mail->Password   = 'utwo mrcm qybu eoog'; // Senha de app do Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('SEU_EMAIL@gmail.com', 'Contato do Site');
    $mail->addAddress('delsonafredo09@gmail.com', 'Você'); // Ou outro destinatário

    $mail->isHTML(true);
    $mail->Subject = 'Novo contato pelo site';
    $mail->Body    = "<b>Nome:</b> $nome<br><b>E-mail:</b> $email<br><b>Telefone:</b> $telefone<br><b>Mensagem:</b> $mensagem";
    $mail->AltBody = "Nome: $nome\nE-mail: $email\nTelefone: $telefone\nMensagem: $mensagem";

    $mail->send();
    echo json_encode(['status' => 'ok', 'mensagem' => 'Mensagem enviada com sucesso!']);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao enviar: ' . $mail->ErrorInfo]);
}
?>