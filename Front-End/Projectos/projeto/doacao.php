<?php
// Incluindo a conexão com o banco de dados
include('conexao.php');

// Função para buscar dados de doações do banco
function getDoacoes() {
    global $conexao;
    $query = "SELECT tipo_sanguineo, COUNT(*) AS quantidade FROM doacoes GROUP BY tipo_sanguineo";
    $result = $conexao->query($query);
    
    // Criando o array de resposta
    $doacoes = [];
    while ($row = $result->fetch_assoc()) {
        $doacoes[] = $row;
    }
    
    return $doacoes;
}

header('Content-Type: application/json');
echo json_encode(getDoacoes());
?>