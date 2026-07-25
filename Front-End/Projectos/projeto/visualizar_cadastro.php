<?php
include 'conexao.php';
$id = intval($_GET['id']);
$stmt = $conexao->prepare("SELECT nome_completo, email, telefone, tipo_sanguineo FROM cadastro WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $email, $telefone, $tipo);
$stmt->fetch();
echo json_encode([
    'nome_completo' => $nome,
    'email' => $email,
    'telefone' => $telefone,
    'tipo_sanguineo' => $tipo
]);
?>
