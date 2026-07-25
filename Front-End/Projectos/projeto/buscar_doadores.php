<?php

include 'conexao.php';

// Troque 'doadores' pelo nome correto da sua tabela de doadores, se for diferente
$result = $conexao->query("SELECT nome_completo, email, telefone, tipo_sanguineo FROM doadores");

$doadores = [];
while ($row = $result->fetch_assoc()) {
    $doadores[] = $row;
}
echo json_encode($doadores);
?>