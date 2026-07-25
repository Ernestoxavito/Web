<?php
include('conexao.php');

$sql = "SELECT * FROM agendamentos ORDER BY data_agendamento DESC";
$result = $conexao->query($sql);

echo '<table border="1" width="100%">';
echo '<tr><th>Nome</th><th>BI</th><th>Data Agendamento</th><th>Telefone</th></tr>';

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nome_completo']}</td>
            <td>{$row['bi']}</td>
            <td>{$row['data_agendamento']}</td>
            <td>{$row['telefone']}</td>
          </tr>";
}

echo '</table>';
?>
