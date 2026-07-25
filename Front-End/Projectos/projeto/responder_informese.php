<?php
// filepath: c:\xampp\htdocs\projeto\responder_informese.php
include 'conexao.php';
$id = intval($_POST['id']);
$resposta = trim($_POST['resposta']);
$stmt = $conexao->prepare("UPDATE informese SET resposta=?, data_resposta=NOW() WHERE id=?");
$stmt->bind_param("si", $resposta, $id);
if ($stmt->execute()) {
    echo "Resposta enviada!";
} else {
    echo "Erro ao responder!";
}