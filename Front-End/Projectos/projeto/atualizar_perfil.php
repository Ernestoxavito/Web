<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    exit('Não autorizado');
}

$id = $_SESSION['usuario_id'];
$nome = trim($_POST['nome_completo']);
$email = trim($_POST['email']);
$telefone = trim($_POST['telefone']);
$endereco = trim($_POST['endereco']);

$stmt = $conexao->prepare("UPDATE Cadastro SET nome_completo=?, email=?, telefone=?, endereco=? WHERE id=?");
$stmt->bind_param("ssssi", $nome, $email, $telefone, $endereco, $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Erro ao atualizar perfil.";
}
$stmt->close();
?>