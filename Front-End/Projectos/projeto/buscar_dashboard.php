<?php

include 'conexao.php';

$hoje = date('Y-m-d');
$amanha = date('Y-m-d', strtotime('+1 day'));

$doadoresHoje = $conexao->query("SELECT COUNT(*) FROM doadores WHERE DATE(data_cadastro) = '$hoje'")->fetch_row()[0];
// Remova ou comente a linha abaixo:
// $triagensPendentes = $conexao->query("SELECT COUNT(*) FROM triagens WHERE status = 'pendente' AND DATE(data) = '$hoje'")->fetch_row()[0];
$coletasHoje = $conexao->query("SELECT COUNT(*) FROM coletas WHERE DATE(data) = '$hoje'")->fetch_row()[0];
$agendamentosAmanha = $conexao->query("SELECT COUNT(*) FROM agendamentos WHERE DATE(data_agendamento) = '$amanha'")->fetch_row()[0];

echo json_encode([
  'doadoresHoje' => $doadoresHoje,
  // 'triagensPendentes' => $triagensPendentes,
  'coletasHoje' => $coletasHoje,
  'agendamentosAmanha' => $agendamentosAmanha
]);
?>