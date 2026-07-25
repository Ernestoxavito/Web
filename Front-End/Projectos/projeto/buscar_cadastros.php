<?php
include 'conexao.php';

$sql = "SELECT id, nome_completo, email, telefone, tipo_sanguineo FROM Cadastro ORDER BY id DESC LIMIT 10";
$resultado = $conexao->query($sql);

$dados = array();
while ($row = $resultado->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);
$conexao->close();
?>
