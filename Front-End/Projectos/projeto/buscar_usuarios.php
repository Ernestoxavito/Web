<?php

include 'conexao.php';
$result = $conexao->query("SELECT id, nome_completo, email, telefone, tipo_sanguineo FROM usuarios ORDER BY nome_completo");
$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}
echo json_encode($usuarios);
?>