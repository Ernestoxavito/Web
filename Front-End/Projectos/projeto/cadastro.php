<?php


session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = trim($_POST['nome']);
    $bi = trim($_POST['bi']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['dataNascimento'];
    $fator_rh = $_POST['fatorRh'];
    $genero = $_POST['genero'];
    $tipo_sanguineo = $_POST['tipoSanguineo'];
    $nivel_acesso = $_POST['nivelAcesso'];
    $senha = $_POST['senha'];
    $confirmaSenha = $_POST['confirmaSenha'];
    $termos_aceitos = isset($_POST['termos']) ? 1 : 0;

    $data_atual = date("Y-m-d");

    if ($data_nascimento > $data_atual) {
    echo "<div class='alert alert-danger'>A data de nascimento não pode ser no futuro!</div>";
    exit;
}

// Validação para não permitir menor de 16 anos
$hoje = new DateTime();
$data_nasc = DateTime::createFromFormat('Y-m-d', $data_nascimento);
$idade = $hoje->diff($data_nasc)->y;
if ($idade < 16) {
    echo "<div class='alert alert-danger'>Você precisa ter pelo menos 16 anos para se cadastrar.</div>";
    exit;
}

if (!preg_match('/^[0-9]{9}$/', $telefone)) {
    echo "<div class='alert alert-danger'>O telefone deve conter 9 dígitos.</div>";
    exit;
}

if (!preg_match('/^[0-9]{9}[A-Za-z]{2}[0-9]{3}$/', $bi)) {
    echo "<div class='alert alert-danger'>BI inválido. Formato: 9 números, 2 letras e 3 números (ex: 123456789AB123).</div>";
    exit;
}

if ($senha !== $confirmaSenha) {
    echo "<div class='alert alert-danger'>As senhas não coincidem!</div>";
    exit;
    } else {
        // Verifica se o e-mail já existe
        $verifica_email = $conexao->prepare("SELECT id FROM Cadastro WHERE email = ?");
        $verifica_email->bind_param("s", $email);
        $verifica_email->execute();
        $verifica_email->store_result();
        if ($verifica_email->num_rows > 0) {
            echo "<div class='alert alert-danger'>E-mail já cadastrado!</div>";
            $verifica_email->close();
            $conexao->close();
            exit;
        }
        $verifica_email->close();

        // Verifica se o telefone já existe
        $verifica_tel = $conexao->prepare("SELECT id FROM Cadastro WHERE telefone = ?");
        $verifica_tel->bind_param("s", $telefone);
        $verifica_tel->execute();
        $verifica_tel->store_result();
        if ($verifica_tel->num_rows > 0) {
            echo "<div class='alert alert-danger'>Telefone já cadastrado!</div>";
            $verifica_tel->close();
            $conexao->close();
            exit;
        }
        $verifica_tel->close();

        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Cadastro (nome_completo, bi, email, telefone, data_nascimento, fator_rh, genero, tipo_sanguineo, nivel_acesso, senha_hash, termos_aceitos)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            echo "<div class='alert alert-danger'>Erro na preparação: " . $conexao->error . "</div>";
            exit;
        }

        $stmt->bind_param("ssssssssssi", $nome_completo, $bi, $email, $telefone, $data_nascimento, $fator_rh, $genero, $tipo_sanguineo, $nivel_acesso, $senha_hash, $termos_aceitos);

        if ($stmt->execute()) {
            // Salva os dados do usuário na sessão (opcional)
            $_SESSION['usuario_id'] = $stmt->insert_id;
            $_SESSION['usuario_nome'] = $nome_completo;
            $_SESSION['usuario_email'] = $email;
            $_SESSION['nivel_acesso'] = $nivel_acesso;

   
           
               
echo "
<div style='
    min-height: 100vh;
    width: 100vw;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0;
    margin: 0;
    background: url(\"img/bg-home.svg\") no-repeat center center;
    background-size: cover;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
'>
    <div style=\"
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 16px #e57373;
        padding: 32px 28px 24px 28px;
        max-width: 400px;
        text-align: center;
    \">
        <div style='margin-bottom:18px;'>
            <svg width='60' height='60' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <circle cx='12' cy='12' r='12' fill='#388e3c'/>
                <path d='M7 13.5L10.5 17L17 10' stroke='#fff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/>
            </svg>
        </div>
        <h3 style='color:#388e3c;margin-bottom:10px;'>Cadastro realizado com sucesso!</h3>
        <p style='color:#7b241c;font-size:1.1rem;margin-bottom:24px;'>Agora você já pode acessar sua conta e salvar vidas!</p>
        <a href='login.php' class='btn btn-success' style='padding:10px 32px;font-size:1.1rem;border-radius:8px;'>Ir para Login</a>
    </div>
</div>
";

        } else {
            echo "<div class='alert alert-danger'>Erro ao cadastrar: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    $conexao->close();
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cadastre-se</title>
    <link rel="shortcut icon" href="../assets/img/icon/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Rubik:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-grid.min.css">
    <link rel="stylesheet" href="src/assets/css/cadastroStyle.css">
    <!DOCTYPE html>
<html lang="pt-BR">
<head>
    </script>
</head>

<body>
    <section class="container-cadastro">
        <div class="bg-cadastro">
            <header>
                <div class="navBar">
                    <img src="img/icon/Logo.svg" alt="Logo E-sangue" class="logo" />
                    <nav class="nav-container">
                        <a href="Index.php" class="hv-home">Home</a>
                        <a href="login.php" class="hv-servico">Login</a>
                        <a href="cadastro.php" class="hv-informacao">Cadastro</a>
                    </nav>
                </div>
            </header>

            <h5>Olá, cadastre-se para salvar vidas com sua doação!</h5>
            <img class="svg-cadastro" src="img/svg-cadastro.svg" alt="Ilustração" />
        </div>

        <div class="btn-voltar-mob">
            <a href="../../Index.php">Voltar</a>
        </div>

        <div class="form-cadastro">
            <div class="box-form">
                <div>
                    <h5>Digite suas informações</h5>
                    <p>Para continuar precisamos de alguns dos seus dados.</p>
                </div>
                
                <form action="cadastro.php" method="POST">
                    <label for="nomeCompleto">
                        <input class="input" type="text" id="nomeCompleto" name="nome" placeholder="Nome de registro" required />
                    </label>

                    <label for="cpf">
                        <input class="input" type="text" id="cpf" name="bi" placeholder="BI" pattern="[0-9]{9}[A-Za-z]{2}[0-9]{3}" maxlength="14" required />
                    </label>

                    <div class="box-input1">
                        <label for="email">
                            <input class="inputP1" type="email" id="email" name="email" placeholder="E-mail" required />
                        </label>

                        <label for="tel">
                            <input class="inputP2" type="text" id="tel" name="telefone" placeholder="Telefone " pattern="[0-9]{9}" maxlength="9" required />
                        </label>
                    </div>

                    <label for="dataN">
                        <input class="input" type="date" id="data" name="dataNascimento" max="<?php echo date('Y-m-d'); ?>" required />
                    </label>

                    <select class="input" name="fatorRh" id="FatorRH" required>
                        <option selected value="">Fator RH</option>
                        <option value="Rh+">Rh positivo (Rh+)</option>
                        <option value="Rh-">Rh negativo (Rh-)</option>
                    </select>

                    <div class="box-input2">
                        <select class="inputP1" name="genero" id="genero" required>
                            <option selected value="">Gênero</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                        </select>
                        <select class="inputP2" name="tipoSanguineo" id="tipoSanguineo" required>
                            <option selected value="">Tipo Sanguíneo</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>

                    <select class="input" name="nivelAcesso" id="nivelAcesso" required>
                        <option selected value="">Nível de Acesso</option>
                        <option value="Doador">Doador</option>
                       
                    </select>

                    <div class="box-password">
                        <h6>Para finalizar precisamos de uma senha</h6>
                        <label for="password">
                            <input class="input" type="password" id="password" name="senha" placeholder="Digite sua senha" required />
                        </label>
                        <label for="checkPassword">
                            <input class="input" type="password" id="checkPassword" name="confirmaSenha" placeholder="Confirmar senha" required />
                        </label>
                    </div>

                    <div class="check">
                        <input class="checkbox" type="checkbox" id="Termos" name="termos" required />
                        <label class="text" for="Termos">Concordo com todos os termos.</label>
                    </div>

                    <button id="doneBtn" type="submit">Finalizar cadastro</button>
                </form>

                <div id="mensagem"></div>
            </div>
        </div>
    </section>

    
                <!-- Dados carregados dinamicamente aqui -->
            </tbody>
        </table>
    </section>

    <!-- JavaScript para envio do formulário e carregamento dinâmico -->
    <script>
           success: function (response) {
    $('#mensagem').html(response);
    if (response.includes('Cadastro realizado com sucesso')) {
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 2000);
}
    $('#formCadastro')[0].reset();
}

        $(function () {
            $('#formCadastro').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'cadastrar.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#mensagem').html('<div>' + response + '</div>');
                        carregarCadastros();
                        $('#formCadastro')[0].reset();
                    }
                });
            });
                
                 if ($stmt->execute()) {
    echo "<div class='alert alert-success'>Cadastro realizado com sucesso!</div>";
} else {
    echo "<div class='alert alert-danger'>Erro ao cadastrar: " . $stmt->error . "</div>";
}

            setInterval(carregarCadastros, 5000);

            function carregarCadastros() {
                $.get('buscar_cadastros.php', function (data) {
                    let cadastros = JSON.parse(data);
                    let html = '';

                    cadastros.forEach(function (c) {
                        html += '<tr>' +
                            '<td>' + c.nome_completo + '</td>' +
                            '<td>' + c.email + '</td>' +
                            '<td>' + c.telefone + '</td>' +
                            '<td>' + c.tipo_sanguineo + '</td>' +
                            '</tr>';
                    });

                    $('#tabela-cadastros tbody').html(html);
                });
            }
        });
    </script>
</body>
</html>
