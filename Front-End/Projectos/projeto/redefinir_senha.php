<?php
include 'conexao.php';

$mensagem = '';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($nova_senha && $nova_senha === $confirmar_senha && strlen($nova_senha) >= 6) {
        // Verifica o token e expiração
        $sql = "SELECT id FROM Cadastro WHERE reset_token=? AND reset_expira > NOW()";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Atualiza a senha e limpa o token
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update = $conexao->prepare("UPDATE Cadastro SET senha_hash=?, reset_token=NULL, reset_expira=NULL WHERE id=?");
            $update->bind_param("si", $senha_hash, $user_id);
            $update->execute();

            $mensagem = "Senha redefinida com sucesso! <a href='login.php'>Faça login</a>";
        } else {
            $mensagem = "Token inválido ou expirado.";
        }
        $stmt->close();
    } else {
        $mensagem = "As senhas não coincidem ou são muito curtas.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Redefinir Senha</title>
</head>
<body>
    <div class="container-form">
        <h2>Redefinir Senha</h2>
        <?php if (!empty($mensagem)) : ?>
            <p class="mensagem"><?= $mensagem ?></p>
        <?php endif; ?>
        <?php if (empty($mensagem) || strpos($mensagem, 'sucesso') === false): ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" />
            <input type="password" name="nova_senha" placeholder="Nova senha" required minlength="6" />
            <input type="password" name="confirmar_senha" placeholder="Confirme a nova senha" required minlength="6" />
            <button type="submit">Redefinir Senha</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>