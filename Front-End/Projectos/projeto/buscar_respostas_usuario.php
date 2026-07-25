<?php
// filepath: c:\xampp\htdocs\projeto\buscar_respostas_usuario.php
include 'conexao.php';
$email = $_GET['email'] ?? '';
$stmt = $conexao->prepare("SELECT duvida, resposta FROM informese WHERE email=? ORDER BY data_envio DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$dados = [];
while ($row = $res->fetch_assoc()) {
    $dados[] = $row;
}
echo json_encode($dados);