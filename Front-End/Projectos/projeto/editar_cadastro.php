<?php
include 'conexao.php';
$id = intval($_POST['id']);
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$tipo = $_POST['tipo'];
$stmt = $conexao->prepare("UPDATE cadastro SET nome_completo=?, email=?, telefone=?, tipo_sanguineo=? WHERE id=?");
$stmt->bind_param("ssssi", $nome, $email, $telefone, $tipo, $id);
if ($stmt->execute()) {
    echo "Cadastro atualizado!";
} else {
    echo "Erro ao atualizar!";
}
?>
