<?php

   session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();  
 } 
// banco_sangue.php - Formulário para cadastro de bancos de sangue
include 'conexao.php';


// salvar_banco.php - Processa o cadastro de um novo banco de sangue


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $localizacao = $_POST['localizacao'] ?? '';
    $horario = $_POST['horario_funcionamento'] ?? '';
    $estoque_aa = (int) ($_POST['estoque_aa'] ?? 0);
    $estoque_ab = (int) ($_POST['estoque_ab'] ?? 0);
    $estoque_b = (int) ($_POST['estoque_b'] ?? 0);
    $estoque_o = (int) ($_POST['estoque_o'] ?? 0);

    if ($nome && $localizacao && $horario) {
        $stmt = $conn->prepare("INSERT INTO bancos_sangue (nome, localizacao, horario_funcionamento, estoque_aa, estoque_ab, estoque_b, estoque_o) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiiii", $nome, $localizacao, $horario, $estoque_aa, $estoque_ab, $estoque_b, $estoque_o);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Banco de sangue cadastrado com sucesso!</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao cadastrar: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    } else {
        echo '<div class="alert alert-warning">Preencha todos os campos obrigatórios.</div>';
    }
} else {
    echo '<div class="alert alert-danger">Método inválido.</div>';
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cadastro de Banco de Sangue</title>
    <link rel="shortcut icon" href="../assets/img/icon/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Rubik:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-grid.min.css">
    <link rel="stylesheet" href="src/assets/css/cadastroStyle.css">
     
    <!DOCTYPE html>

  
    
    
</head>
<body class="container py-4">
    <h2>Cadastro de Banco de Sangue</h2>
    <form id="formBancoSangue">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Localização</label>
            <input type="text" name="localizacao" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Horário de Funcionamento</label>
            <input type="text" name="horario_funcionamento" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Estoque Inicial:</label>
            <div class="row">
                <div class="col"><input type="number" name="estoque_aa" placeholder="AA" class="form-control" value="0"></div>
                <div class="col"><input type="number" name="estoque_ab" placeholder="AB" class="form-control" value="0"></div>
                <div class="col"><input type="number" name="estoque_b" placeholder="B" class="form-control" value="0"></div>
                <div class="col"><input type="number" name="estoque_o" placeholder="O" class="form-control" value="0"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-danger">Cadastrar Banco</button>
    </form>
    <div id="mensagem" class="mt-3"></div>

    <script>
        document.getElementById('formBancoSangue').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('salvar_banco.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('mensagem').innerHTML = data;
                this.reset();
            });
        });
    </script>
</body>
</html>
