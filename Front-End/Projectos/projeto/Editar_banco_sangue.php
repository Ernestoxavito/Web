<?php
// editar_banco.php - Formulário para edição de banco de sangue existente
include 'conexao.php';

if (!isset($_GET['id'])) {
    echo "ID do banco não informado.";
    exit;
}

$id = (int) $_GET['id'];
$result = $conn->query("SELECT * FROM bancos_sangue WHERE id = $id");
$banco = $result->fetch_assoc();

if (!$banco) {
    echo "Banco de sangue não encontrado.";
    exit;
}
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
    <title>Editar Banco de Sangue</title>
   
</head>
<body class="container py-4">
    <h2>Editar Banco de Sangue</h2>
    <form action="atualizar_banco.php" method="POST">
        <input type="hidden" name="id" value="<?= $banco['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($banco['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Localização</label>
            <input type="text" name="localizacao" class="form-control" value="<?= htmlspecialchars($banco['localizacao']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Horário de Funcionamento</label>
            <input type="text" name="horario_funcionamento" class="form-control" value="<?= htmlspecialchars($banco['horario_funcionamento']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Estoque:</label>
            <div class="row">
                <div class="col"><input type="number" name="estoque_aa" class="form-control" value="<?= $banco['estoque_aa'] ?>"></div>
                <div class="col"><input type="number" name="estoque_ab" class="form-control" value="<?= $banco['estoque_ab'] ?>"></div>
                <div class="col"><input type="number" name="estoque_b" class="form-control" value="<?= $banco['estoque_b'] ?>"></div>
                <div class="col"><input type="number" name="estoque_o" class="form-control" value="<?= $banco['estoque_o'] ?>"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Banco</button>
        <a href="listar_bancos.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>
