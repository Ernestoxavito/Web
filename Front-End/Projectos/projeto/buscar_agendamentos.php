<?php

include 'conexao.php';

// Ajuste os nomes dos campos conforme estão na sua tabela!
$result = $conexao->query("
    SELECT 
        nome_completo AS nome_doador, 
        fator_RH AS tipo, 
        data_agendamento AS data, 
        hora_agendamento AS hora, 
        quantidade, 
        local, 
        status 
    FROM agendamentos 
    ORDER BY id DESC
");

$result = $conexao->query("SELECT nome_completo, bi, email, telefone, data, fator_RH, genero, data_agendamento FROM agendamentos ORDER BY id DESC");
$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}
echo json_encode($agendamentos);
?>
