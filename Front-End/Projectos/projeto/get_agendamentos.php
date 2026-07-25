<?php
include('conexao.php'); // Conexão com o banco de dados

// Consulta para pegar os agendamentos do banco de dados
$query = "SELECT nome_completo, data_agendamento, telefone, email, fator_RH FROM agendamentos ORDER BY data_agendamento DESC";
$result = $conexao->query($query);

// Armazena os agendamentos em um array
$agendamentos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}

// Retorna os agendamentos em formato JSON
echo json_encode($agendamentos);
?>