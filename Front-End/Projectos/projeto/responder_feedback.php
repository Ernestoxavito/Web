<?php

include 'conexao.php';
$id = $_POST['id'];
$email = $_POST['email'];
$resposta = $_POST['resposta'];

// Exemplo: salvar resposta no banco (crie o campo se necessário)
$conn->query("UPDATE feedback SET resposta='$resposta' WHERE id=$id");

// Opcional: enviar e-mail para o usuário
if ($email) {
    mail($email, "Resposta ao seu feedback", $resposta, "From: admin@seudominio.com");
}

echo "Resposta enviada!";
?>