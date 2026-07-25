<?php
include 'conexao.php';

$result = $conexao->query("SELECT id, nome, comentario FROM feedbacks ORDER BY id DESC");
$feedbacks = [];
while ($row = $result->fetch_assoc()) {
    $feedbacks[] = $row;
}
header('Content-Type: application/json');
echo json_encode($feedbacks);
?>