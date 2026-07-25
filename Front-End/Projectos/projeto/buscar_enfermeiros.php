<?php

include 'conexao.php';
$result = $conexao->query("SELECT id, nome_completo, email, telefone, bi FROM cadastro WHERE nivel_acesso = 'Enfermeiro'");
$enfermeiros = [];
while ($row = $result->fetch_assoc()) {
    $enfermeiros[] = $row;
}
echo json_encode($enfermeiros);
?>