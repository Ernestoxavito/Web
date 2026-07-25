<?php
// listar_bancos.php - Exibe a lista de bancos de sangue cadastrados
include 'conexao.php';

$sql = "SELECT * FROM bancos_sangue";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Bancos de Sangue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Lista de Bancos de Sangue</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Localização</th>
                <th>Horário</th>
                <th>Estoque AA</th>
                <th>Estoque AB</th>
                <th>Estoque B</th>
                <th>Estoque O</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['localizacao']) ?></td>
                    <td><?= htmlspecialchars($row['horario_funcionamento']) ?></td>
                    <td><?= $row['estoque_aa'] ?></td>
                    <td><?= $row['estoque_ab'] ?></td>
                    <td><?= $row['estoque_b'] ?></td>
                    <td><?= $row['estoque_o'] ?></td>
                    <td>
                        <a href="editar_banco.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="excluir_banco.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este banco?')">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
