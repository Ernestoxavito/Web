<?php
include 'conexao.php';
$id = intval($_POST['id']);
$nome = $_POST['nome'];
$data = $_POST['data'];
$horario = $_POST['horario'];
$tipo = $_POST['tipo'];
$status = $_POST['status'];
$stmt = $conexao->prepare("UPDATE agendamentos SET nome_completo=?, data_agendamento=?, horario=?, tipo_sanguineo=?, status=? WHERE id=?");
$stmt->bind_param("sssssi", $nome, $data, $horario, $tipo, $status, $id);
if ($stmt->execute()) {
    echo "Agendamento atualizado!";
} else {
    echo "Erro ao atualizar!";
}
?>