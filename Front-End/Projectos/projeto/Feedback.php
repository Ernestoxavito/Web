<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Conexão com o banco de dados
include 'conexao.php';

// Determinar a página atual
$currentPage = basename($_SERVER['PHP_SELF'], ".php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $comentario = $_POST['comentario'];

    $stmt = $conexao->prepare("INSERT INTO feedbacks (nome, comentario) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $comentario);
    if ($stmt->execute()) {
        echo "<script>
            alert('Obrigado pelo seu feedback!');
            window.location.href='index.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Erro ao enviar feedback.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-grid.min.css">
    <link rel="stylesheet" href="staly.css">
    <link rel="stylesheet" href="schedulingStyle.css" />
    <link rel="stylesheet" href="main.css" />
    <link rel="stylesheet" href="style.css">
    <title>Feedback - Sistema de Doação de Sangue</title>
</head>
<body>

<!-- CONTEÚDO -->
<section id="feedback">
    <div class="container">
        <div class="feedback-form">
            <h2 class="title">Deixe seu <span class="highlight">Feedback</span></h2>
            <p>Queremos saber sua opinião! Seu feedback é muito importante para nós, para que possamos melhorar cada vez mais.</p>

            <form method="post" action="Feedback.php" style="max-width:400px;margin:40px auto;">
    <div class="form-group">
        <label for="nome">Seu nome:</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="comentario">Comentário:</label>
        <textarea class="form-control" id="comentario" name="comentario" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-danger">Enviar Feedback</button>
</form>
        </div>
    </div>
</section>
<!-- FIM CONTEÚDO -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>