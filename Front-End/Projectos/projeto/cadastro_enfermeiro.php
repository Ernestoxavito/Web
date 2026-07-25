<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_completo']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $bi = trim($_POST['bi']);
    $senha = trim($_POST['senha']);
    $confirma = trim($_POST['confirma_senha']);
    $nivel = 'Enfermeiro';

    if ($senha !== $confirma) {
        echo json_encode(['success' => false, 'message' => 'As senhas não coincidem!']);
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $verifica = $conexao->prepare("SELECT id FROM cadastro WHERE email = ? OR bi = ?");
    if (!$verifica) {
        echo json_encode(['success' => false, 'message' => 'Erro no prepare: ' . $conexao->error]);
        exit;
    }
    $verifica->bind_param("ss", $email, $bi);
    $verifica->execute();
    $verifica->store_result();
    if ($verifica->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email ou BI já cadastrado!']);
        exit;
    }
    $verifica->close();

    $stmt = $conexao->prepare("INSERT INTO cadastro (nome_completo, email, telefone, bi, senha_hash, nivel_acesso) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erro no prepare: ' . $conexao->error]);
        exit;
    }
    $stmt->bind_param("ssssss", $nome, $email, $telefone, $bi, $senha_hash, $nivel);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Enfermeiro cadastrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar enfermeiro!']);
    }
    $stmt->close();
}
?>