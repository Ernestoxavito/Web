<?php
session_start();
// Função para login do administrador
function login($usuario, $senha) {
    global $conexao;
    $query = "SELECT * FROM administradores WHERE usuario = ? AND senha = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param('ss', $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Função para buscar doações
function getDoacoes() {
    global $conexao;
    $query = "SELECT tipo_sanguineo, COUNT(*) AS quantidade FROM doacoes GROUP BY tipo_sanguineo";
    return $conexao->query($query);
}

// Função para buscar agendamentos
function getAgendamentos() {
    global $conexao;
    $query = "SELECT MONTH(data_agendamento) AS mes, COUNT(*) AS total FROM agendamentos GROUP BY MONTH(data_agendamento)";
    return $conexao->query($query);
}

// Função para registrar doações
function registrarDoacao($tipo_sanguineo, $quantidade) {
    global $conexao;
    for ($i = 0; $i < $quantidade; $i++) {
        $stmt = $conexao->prepare("INSERT INTO doacoes (tipo_sanguineo) VALUES (?)");
        $stmt->bind_param('s', $tipo_sanguineo);
        $stmt->execute();
    }
}
?>