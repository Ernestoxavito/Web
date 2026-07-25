<?php
include 'conexao.php';

// Mensagens de feedback
if (isset($_GET['atualizado']) && $_GET['atualizado'] == 1) {
    echo '<div class="alert alert-success">Banco de sangue atualizado com sucesso!</div>';
}
if (isset($_GET['excluido']) && $_GET['excluido'] == 1) {
    echo '<div class="alert alert-success">Banco de sangue excluído com sucesso!</div>';
}

// Busca por filtro
$busca = $_GET['busca'] ?? '';
$sql = "SELECT * FROM bancos_sangue";
if (!empty($busca)) {
    $busca = $conn->real_escape_string($busca);
    $sql .= " WHERE nome LIKE '%$busca%' OR localizacao LIKE '%$busca%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="src/assets/img/icon/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="src/assets/css/bootstrap-grid.min.css" />
        <link rel="stylesheet" href="src/assets/css/schedulingStyle.css" />
        <link rel="stylesheet" href="src/assets/css/main.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
</head>
<body class="container py-4">
    <h2 class="mb-4">Bancos de Sangue Cadastrados</h2>

    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou localização" value="<?= htmlspecialchars($busca) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Localização</th>
                <th>Horário</th>
                <th>A+</th>
                <th>AB</th>
                <th>B</th>
                <th>O</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['localizacao']) ?></td>
                    <td><?= htmlspecialchars($row['horario_funcionamento']) ?></td>
                    <td><?= $row['estoque_aa'] ?></td>
                    <td><?= $row['estoque_ab'] ?></td>
                    <td><?= $row['estoque_b'] ?></td>
                    <td><?= $row['estoque_o'] ?></td>
                    <td>
                        <a href="editar_banco.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="excluir_banco.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este banco?')">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Voltar ao Início</a>
</body>
</html>
