<?php
include 'conexao.php';

// Troque 'bancos_sangue' pelo nome correto da sua tabela, se for diferente
$result = $conexao->query("SELECT nome, localizacao, horario_funcionamento FROM bancos_sangue");

$bancos = [];
while ($row = $result->fetch_assoc()) {
    $bancos[] = $row;
}
echo json_encode($bancos);
?>