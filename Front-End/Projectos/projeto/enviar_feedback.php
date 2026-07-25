<?php
session_start();
include 'conexao.php';
$feedback = trim($_POST['feedback']);
$nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : null;

if ($feedback) {
    $stmt = $conexao->prepare("INSERT INTO feedbacks (nome, comentario) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $feedback);
    $stmt->execute();
    $stmt->close();
    echo "ok";
} else {
    echo "erro";
}
?>