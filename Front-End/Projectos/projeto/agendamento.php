<?php
 
 session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
include 'conexao.php';

// AJAX: Total de doadores hoje
if (isset($_GET['ajax']) && $_GET['ajax'] === 'total_doadores_hoje') {
    $hoje = date('Y-m-d');
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM agendamentos WHERE data_agendamento = ?");
    $stmt->bind_param("s", $hoje);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    echo json_encode(['total' => $total]);
    exit;
}

// AJAX: Coletas hoje
if (isset($_GET['ajax']) && $_GET['ajax'] === 'coletas_hoje') {
    $hoje = date('Y-m-d');
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM agendamentos WHERE data_agendamento = ?");
    $stmt->bind_param("s", $hoje);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    echo json_encode(['total' => $total]);
    exit;
}

// AJAX: Agendamentos amanhã
if (isset($_GET['ajax']) && $_GET['ajax'] === 'agendamentos_amanha') {
    $amanha = date('Y-m-d', strtotime('+1 day'));
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM agendamentos WHERE data_agendamento = ?");
    $stmt->bind_param("s", $amanha);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    echo json_encode(['total' => $total]);
    exit;
}

// AGENDAMENTO (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    $nome_completo = trim($_POST['nome_completo']);
    $bi = trim($_POST['bi']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data'];
    $fator_rh = $_POST['fator_RH'];
    $genero = $_POST['genero'] ?? '';
    $data_agendamento = $_POST['data_agendamento'];
    $data_hoje = date("Y-m-d");

    // Limite de 4 agendamentos por dia
    $stmt_limite = $conexao->prepare("SELECT COUNT(*) FROM agendamentos WHERE data_agendamento = ?");
    $stmt_limite->bind_param("s", $data_agendamento);
    $stmt_limite->execute();
    $stmt_limite->bind_result($total_agendados);
    $stmt_limite->fetch();
    $stmt_limite->close();

    if ($total_agendados >= 4) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Limite de 4 agendamentos por dia atingido para esta data. Escolha outro dia.']);
        $conexao->close();
        exit;
    }

    // Validações
    if ($data_nascimento > $data_hoje) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'A data de nascimento não pode ser no futuro!']);
        exit;
    } elseif (!preg_match('/^[0-9]{9}$/', $telefone)) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'O telefone deve conter 9 dígitos.']);
        exit;
    } elseif (!preg_match('/^[0-9]{9}[A-Za-z]{2}[0-9]{3}$/', $bi)) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'BI inválido. Formato correto: 9 números, 2 letras e 3 números.']);
        exit;
    } else {
        // Verifica duplicidade de agendamento pelo mesmo BI
        $verifica = $conexao->prepare("SELECT id FROM agendamentos WHERE bi = ?");
        if (!$verifica) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao preparar a verificação: ' . $conexao->error]);
            exit;
        }
        $verifica->bind_param("s", $bi);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Este BI já tem um agendamento registrado!']);
            $verifica->close();
            $conexao->close();
            exit;
        } else {
            $sql = "INSERT INTO agendamentos 
                    (nome_completo, bi, email, telefone, data, fator_RH, genero, data_agendamento) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssssssss", 
                $nome_completo, 
                $bi, 
                $email, 
                $telefone, 
                $data_nascimento, 
                $fator_rh, 
                $genero, 
                $data_agendamento
            );

            if ($stmt->execute()) {
                // Atualiza o estoque de sangue
                $update = $conexao->prepare("UPDATE estoque_sangue SET quantidade = quantidade + 1 WHERE tipo_sanguineo = ?");
                $update->bind_param("s", $fator_rh);
                $update->execute();
                $update->close();

                // Envia e-mail em tempo real ao doador
                $assunto = "Agendamento de Doação Confirmado";
                $mensagem = "Olá $nome_completo,\n\nSeu agendamento para doação de sangue foi realizado com sucesso para o dia $data_agendamento.\n\nPor favor, compareça com 15 minutos de antecedência e traga um documento de identidade com foto.\n\nAtenciosamente,\nEquipe de Gestão de Doações de Sangue";
                @mail($email, $assunto, $mensagem);

                echo json_encode(['status' => 'sucesso', 'mensagem' => 'Agendamento realizado com sucesso!']);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao agendar: ' . $stmt->error]);
            }
            $stmt->close();
        }
        $verifica->close();
    }
    $conexao->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="assets/img/icon/favicon.ico" type="image/x-icon" />
  <title>Agende sua consulta</title>
  <link rel="stylesheet" href="style.css">
  <style>
    #mensagem {
      margin-top: 15px;
      font-size: 1.1em;
      font-weight: bold;
      text-align: center;
    }
    .btn-voltar {
      display: inline-block;
      margin: 20px 0 0 20px;
      padding: 8px 18px;
      background: #d32f2f;
      color: #fff;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.2s;
    }
    .btn-voltar:hover {
      background: #a93226;
      color: #fff;
      text-decoration: none;
    }
    .gender-details .category {
      display: flex;
      gap: 20px;
      align-items: center;
    }
    .gender-details input[type="radio"] {
      accent-color: #d32f2f;
      margin-right: 5px;
    }
    .gender-details label {
      cursor: pointer;
      user-select: none;
    }
  </style>
</head>
<body>
  <a href="Index.php" class="btn-voltar">← Voltar</a>
  <div>
    <div class="logo">
      <a href="Index.php">
        <img src="src/assets/img/icon/Logo.svg" alt="Banner" class="logo" />
      </a>
    </div>
    <div class="container">
      <div class="title">Agendamento</div>
      <div class="infor-part">
        <span class="infor">informações</span>
      </div>
      <div class="content">
        <!-- Registration form -->
        <form id="formAgendamento" action="agendamento.php" method="POST" autocomplete="off">
          <div class="user-details">
            <div class="input-box">
              <span class="details">Nome completo</span>
              <input type="text" name="nome_completo" placeholder="Seu nome completo" required>
            </div>
            <div class="input-box">
              <span class="details">Data de Nascimento</span>
              <input type="date" name="data" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="input-box">
              <span class="details">BI</span>
              <input type="text" name="bi" placeholder="BI" pattern="[0-9]{9}[A-Za-z]{2}[0-9]{3}" maxlength="14" required>
            </div>
            <div class="input-box">
              <span class="details">Número de telefone</span>
              <input type="tel" name="telefone" placeholder="Número de Telefone" pattern="[0-9]{9}" maxlength="9" required>
            </div>
            <div class="input-box">
              <span class="details">Fator RH</span>
              <select name="fator_RH" required>
                <option value="">Selecione o fator RH</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
              </select>
            </div>
            <div class="input-box">
              <span class="details">E-mail</span>
              <input type="email" name="email" placeholder="Digite seu E-mail" required>
            </div>
          </div>
          <div class="gender-details">
            <span class="gender-title">Gênero</span>
            <div class="category">
              <label>
                <input type="radio" name="genero" value="Masculino" required>
                <span class="dot one"></span>
                <span class="gender">Masculino</span>
              </label>
              <label>
                <input type="radio" name="genero" value="Feminino">
                <span class="dot two"></span>
                <span class="gender">Feminino</span>
              </label>
            </div>
          </div>
          <div class="input-box">
            <span class="details">Data de Agendamento</span>
            <input type="date" name="data_agendamento" required>
          </div>
          <div class="button">
            <input type="submit" value="Agendar">
          </div>
        </form>
        <div id="mensagem"></div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
   $(function () {
    $('#formAgendamento').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: 'agendamento.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                let res = typeof response === "object" ? response : JSON.parse(response);
                if (res.status === 'sucesso') {
                    $('#mensagem').html('<div style="color:green;">' + res.mensagem + '</div>').show();
                    $('#formAgendamento')[0].reset();
                    // Remove seleção dos radios manualmente (para garantir)
                    $('input[name="genero"]').prop('checked', false);
                    setTimeout(function() { $('#mensagem').fadeOut('slow', function(){ $(this).html('').show(); }); }, 4000);
                } else {
                    $('#mensagem').html('<div style="color:red;">' + res.mensagem + '</div>').show();
                    setTimeout(function() { $('#mensagem').fadeOut('slow', function(){ $(this).html('').show(); }); }, 4000);
                }
            }
        });
    });
    // Garante que a mensagem apareça sempre que for exibida
    $('#mensagem').show();
   });
  </script>
</body>
</html>