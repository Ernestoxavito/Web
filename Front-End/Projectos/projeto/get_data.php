<?php
// Incluindo a conexão com o banco de dados
include('conexao.php');

// Função para buscar dados de doações
function getDoacoes() {
    global $conexao;
    $query = "SELECT tipo_sanguineo, COUNT(*) AS quantidade FROM doacoes GROUP BY tipo_sanguineo";
    $result = $conexao->query($query);
    $doacoes = ['novos' => 0, 'recorrentes' => 0]; // Valores iniciais
    while ($row = $result->fetch_assoc()) {
        // Defina a lógica de 'novos' e 'recorrentes'
        // Exemplo: Se o tipo sanguíneo for A+, consideramos 'novos' ou 'recorrentes'
        if ($row['tipo_sanguineo'] == 'A+') {
            $doacoes['novos'] = $row['quantidade'];
        } else {
            $doacoes['recorrentes'] = $row['quantidade'];
        }
    }
    return $doacoes;
}

// Função para buscar tipos sanguíneos
function getTiposSanguineos() {
    global $conexao;
    $query = "SELECT tipo_sanguineo, SUM(quantidade) AS total FROM sangue GROUP BY tipo_sanguineo";
    $result = $conexao->query($query);
    $labels = [];
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['tipo_sanguineo'];
        $values[] = $row['total'];
    }
    return ['labels' => $labels, 'values' => $values];
}

// Função para buscar agendamentos
function getAgendamentos() {
    global $conexao;
    $query = "SELECT MONTH(data_agendamento) AS mes, COUNT(*) AS total FROM agendamentos GROUP BY MONTH(data_agendamento)";
    $result = $conexao->query($query);
    $meses = [];
    $valores = [];
    while ($row = $result->fetch_assoc()) {
        $meses[] = date("M", mktime(0, 0, 0, $row['mes'], 10));
        $valores[] = $row['total'];
    }
    return ['meses' => $meses, 'valores' => $valores];
}

// Buscar os dados e enviar em JSON
$doacoes = getDoacoes();
$sangue = getTiposSanguineos();
$agendamentos = getAgendamentos();

echo json_encode([
    'doacoes' => $doacoes,
    'sangue' => $sangue,
    'agendamentos' => $agendamentos
]);
?>