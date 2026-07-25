<?php
header('Content-Type: application/json');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$duvida = trim($_POST['duvida'] ?? '');

if (!$nome || !$email || !$duvida) {
    echo json_encode(['status'=>'erro', 'mensagem'=>'Preencha todos os campos!']);
    exit;
}

// Aqui você pode salvar no banco ou enviar por e-mail
echo json_encode(['status'=>'ok', 'mensagem'=>'Sua dúvida foi enviada! Em breve responderemos por e-mail.']);