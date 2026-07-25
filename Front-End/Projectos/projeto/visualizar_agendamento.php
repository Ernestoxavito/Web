<?php
include 'conexao.php';
$id = intval($_GET['id']);
$stmt = $conexao->prepare("SELECT nome_completo as nome, data_agendamento as data, horario, tipo_sanguineo, status FROM agendamentos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $data, $horario, $tipo, $status);
$stmt->fetch();
echo json_encode([
    'nome' => $nome,
    'data' => $data,
    'horario' => $horario,
    'tipo_sanguineo' => $tipo,
    'status' => $status
]);
?>