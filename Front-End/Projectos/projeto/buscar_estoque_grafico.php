<?php

include 'conexao.php';

// Junta o tipo sanguíneo e o fator RH para exibir, por exemplo, "A Rh+"
$sql = "SELECT 
            CONCAT(ts.tipo, REPLACE(ts.fator_rh, 'Rh', '')) AS tipo_sanguineo, 
            es.quantidade_ml AS quantidade
        FROM estoque_sangue es
        JOIN tipo_sanguineo ts ON es.tipo_sanguineo_id = ts.id
        ORDER BY ts.tipo, ts.fator_rh";

$result = $conexao->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro na consulta: ' . $conexao->error]);
    exit;
}

$labels = [];
$data = [];
while ($row = $result->fetch_assoc()) 
if (empty($labels)) {
    error_log('Nenhum tipo de sangue encontrado no estoque_sangue com correspondência em tipo_sanguineo.');
    $labels[] = $row['tipo_sanguineo']; // Exemplo: "A+"
    $data[] = (int)$row['quantidade'];
}

header('Content-Type: application/json');
echo json_encode(['labels' => $labels, 'data' => $data]);
?>