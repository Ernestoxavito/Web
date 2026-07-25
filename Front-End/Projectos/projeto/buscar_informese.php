<?php
// filepath: c:\xampp\htdocs\projeto\buscar_informese.php
include 'conexao.php';
$res = $conexao->query("SELECT * FROM informese ORDER BY data_envio DESC");
$dados = [];
while ($row = $res->fetch_assoc()) {
    $dados[] = $row;
}
echo json_encode($dados);