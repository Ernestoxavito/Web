<?php
// listar_doadores.php
include 'conexao.php';

$sql = "SELECT nome, email, tipo_sanguineo FROM doadores ORDER BY id DESC";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tipo_sanguineo']) . "</td>";
    echo "<td class='acoes'>
            <button class='ver'>Ver</button>
            <button class='editar'>Editar</button>
            <button class='excluir'>Excluir</button>
          </td>";
    echo "</tr>";
}
?>
