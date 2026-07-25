<?php 
session_start();


include 'conexao.php';

// Inicializa a variável de mensagem

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $nivel_acesso = isset($_POST['N_acesso']) ? $_POST['N_acesso'] : '';

    if (!empty($email) && !empty($senha) && !empty($nivel_acesso)) {
        $sql = "SELECT id, nome_completo, email, senha_hash, nivel_acesso FROM Cadastro WHERE email = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $nome, $email_db, $senha_hash, $nivel);
            $stmt->fetch();

            if (password_verify($senha, $senha_hash)) {
                if ($nivel_acesso === $nivel) {
                    // Envio de e-mail em tempo real
                    $assunto = "Login realizado com sucesso";
                    $mensagem = "Olá $nome,\n\nSeu login foi realizado com sucesso em " . date('d/m/Y H:i') . ".\n\nSe não foi você, por favor, entre em contato com o suporte.";
                    @mail($email_db, $assunto, $mensagem);

                    // Armazena os dados na sessão
                    $_SESSION['usuario_id'] = $id;
                    $_SESSION['usuario_nome'] = $nome;
                    $_SESSION['usuario_email'] = $email_db;
                    $_SESSION['nivel_acesso'] = $nivel;
                  
                    // Redireciona com base no nível de acesso
                    if ($nivel === 'ADM') {
                        $_SESSION['admin_nome'] = $nome;
                        $_SESSION['admin_email'] = $email_db;
                        header('Location: Dashboardd.php');
                        exit();
                    } elseif ($nivel === 'Enfermeiro') {
                        header('Location: chad.php');
                        exit();
                    } elseif ($nivel === 'Doador') {
                        header('Location: index.php');
                        exit();
                    } else {
                        $mensagem = "Nível de acesso inválido!";
                    }
                } else {
                    $mensagem = "Nível de acesso incorreto.";
                }
            } else {
                $mensagem = "Senha incorreta.";
            }
        } else {
            $mensagem = "Usuário não encontrado.";
        }
        $stmt->close();
    } else {
        $mensagem = "Preencha todos os campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="../img/icon/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Rubik:wght@300;400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="bootstrap-grid.min.css" />
    <link rel="stylesheet" href="src/assets/css/loginStyle.css" />  
    
    <title>Login</title>
</head>
<body>
    <div class="btn-voltar-mob">
        <a href="../../Index.php">Voltar</a>
    </div>

    <section class="container-login">
        <div class="login">
            <div class="container-form">
                <div class="box-title">
                    <h6 class="title">Login</h6>
                    <p class="text">Faça o seu login para acessar sua conta!</p>
                </div>

                <?php if (!empty($mensagem)) : ?>
                    <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
                <?php endif; ?>

                <div class="box-form">
                    <form method="POST">
                        <label for="email">
                            <input class="input-email" type="email" name="email" placeholder="E-mail" required />
                        </label>

                        <label for="password">
                            <input class="input-password" type="password" name="senha" placeholder="Senha" required />
                        </label>

                        <label for="N_acesso">
                            <select name="N_acesso" required>
                                <option value="">Selecione o nível de acesso</option>
                                <option value="Doador">Doador</option>
                                <option value="Enfermeiro">Enfermeiro</option>
                                <option value="ADM">ADM</option>
                            </select>
                        </label>

                        <button type="submit" name="login">Login</button>
                    </form>
                </div>

                <div class="check">
                    <input class="checkbox" type="checkbox" id="check" name="lembrar" />
                    <label class="text" for="check">Lembrar?</label>
                   <a class="text" href="recuperar_senha.php">Esqueceu sua senha?</a>
                </div>

                <p class="text">Não possui cadastro? <a class="link-cadastro" href="cadastro.php">Cadastre-se aqui</a></p>
            </div>
        </div>

        <div class="background-login">
            <header>
                <div class="navBar">
                    <nav class="nav-container">
                        <a href="Index.php" class="hv-home text-color-light">Home</a>
                        <a href="login.php" class="hv-servico text-color-light">Login</a>
                        <a href="cadastro.php" class="hv-informacao text-color-light">Cadastro</a>
                    </nav>
                    <img src="img/icon/Logo.svg" alt="Logo E-sangue" class="logo" />
                </div>
            </header>

            <img class="svg" src="img/svg-login.svg" alt="Ilustração" />
        </div>
    </section>
</body>
</html>