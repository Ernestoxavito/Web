<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['nivel_acesso'] !== 'Enfermeiro') {
    header('Location: login.php');
    exit();
}
$admin_nome = $_SESSION['usuario_nome'];

include 'conexao.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Aceitar agendamento (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aceitar_agendamento'])) {
    $id = intval($_POST['id']);

    // Atualiza status
    $stmt = $conexao->prepare("UPDATE agendamentos SET status = 'Confirmado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Busca dados do agendamento
    $stmt2 = $conexao->prepare("SELECT * FROM agendamentos WHERE id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $dados = $stmt2->get_result()->fetch_assoc();

    // Envia e-mail em tempo real
    $nome = $dados['nome_completo'];
    $email = $dados['email'];
    $data_agendamento = $dados['data_agendamento'];
    $data_triagem = date('d/m/Y', strtotime($data_agendamento . ' -1 day'));
    $assunto = "Confirmação de Agendamento de Doação";
    $mensagem = "Olá $nome,<br><br>Seu agendamento para doação de sangue foi confirmado. Solicitamos que compareça para a triagem médica no dia $data_triagem, conforme orientação do nosso sistema.<br><br>Por favor, esteja no local com pelo menos 15 minutos de antecedência. Leve um documento de identidade com foto.<br><br>Atenciosamente,<br>Equipe de Gestão de Doações de Sangue";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'delsonafredo09@gmail.com'; // Seu Gmail
        $mail->Password   = 'utwo mrcm qybu eoog'; // Senha de app do Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('delsonafredo09@gmail.com', 'Hospital Geral');
        $mail->addAddress($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $mensagem;
        $mail->AltBody = strip_tags(str_replace("<br>", "\n", $mensagem));

        $mail->send();
        // E-mail enviado com sucesso
    } catch (Exception $e) {
        // Você pode logar o erro se quiser: $mail->ErrorInfo
        // echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }

    echo json_encode(['status' => 'ok', 'dados' => $dados]);
    exit;
}

// Salvar triagem (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_triagem'])) {
    $agendamento_id = isset($_POST['agendamento_id']) ? intval($_POST['agendamento_id']) : 0;

    // Verifica se o agendamento existe e está confirmado
    $check = $conexao->prepare("SELECT id FROM agendamentos WHERE id = ? AND status = 'Confirmado'");
    $check->bind_param("i", $agendamento_id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows === 0) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Agendamento inválido ou não confirmado.']);
        exit;
    }
    $check->close();

    $nome_doador = $_POST['nome'] ?? '';
    $bi = $_POST['bi'] ?? '';
    $temperatura = floatval(str_replace(',', '.', $_POST['temperatura'] ?? ''));
    $peso = floatval(str_replace(',', '.', $_POST['peso'] ?? ''));
    $status_triagem = $_POST['apto'] ?? '';
    $quantidade_ml = intval($_POST['quantidade'] ?? 0);
    $hora_doacao = $_POST['hora_doacao'] ?? '';
    if (strlen($hora_doacao) === 5) $hora_doacao .= ':00';
    $tipo_sanguineo = $_POST['tipo_sanguineo'] ?? '';
    $data_doacao = $_POST['data_doacao'] ?? '';
    if (strpos($data_doacao, '/')) {
        $data_doacao = date('Y-m-d', strtotime(str_replace('/', '-', $data_doacao)));
    }
    $responsavel_tecnico = $_POST['responsavel_tecnico'] ?? '';
    $local_coleta = $_POST['local_coleta'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';

    // Salva na triagem
    $stmt = $conexao->prepare("INSERT INTO triagem 
        (agendamento_id, nome_doador, bi, temperatura, peso, status_triagem, quantidade_ml, hora_doacao, tipo_sanguineo, data_doacao, responsavel_tecnico, local_coleta, observacoes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no prepare da triagem: ' . $conexao->error]);
        exit;
    }
    $stmt->bind_param("issddssssssss",
        $agendamento_id, $nome_doador, $bi, $temperatura, $peso, $status_triagem, $quantidade_ml, $hora_doacao, $tipo_sanguineo, $data_doacao, $responsavel_tecnico, $local_coleta, $observacoes
    );

    if ($stmt->execute()) {
        // Salva também no relatório clínico
        $stmt2 = $conexao->prepare("INSERT INTO relatorio_clinico 
            (agendamento_id, nome_doador, tipo_sanguineo, quantidade, data_doacao, hora_doacao, status_triagem, responsavel_tecnico, local_coleta, observacoes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt2) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no prepare do relatório clínico: ' . $conexao->error]);
            exit;
        }
        $stmt2->bind_param("ississssss",
            $agendamento_id, $nome_doador, $tipo_sanguineo, $quantidade_ml, $data_doacao, $hora_doacao, $status_triagem, $responsavel_tecnico, $local_coleta, $observacoes
        );
        if ($stmt2->execute()) {
            echo json_encode(['status' => 'ok', 'mensagem' => 'Triagem salvos com sucesso!']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar relatório clínico: ' . $stmt2->error]);
        }
        $stmt2->close();
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar triagem: ' . $stmt->error]);
    }
    $stmt->close();
    exit;
}

// AJAX: Tipos de sangue para gráfico
if (isset($_GET['ajax']) && $_GET['ajax'] === 'tipos_sangue') {
    $sql = "SELECT fator_RH AS tipo_sanguineo, COUNT(*) AS total 
            FROM agendamentos 
            GROUP BY fator_RH 
            ORDER BY FIELD(fator_RH, 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-')";
    $result = $conexao->query($sql);

    $tipos = [];
    $totais = [];
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row['tipo_sanguineo'];
        $totais[] = (int)$row['total'];
    }
    header('Content-Type: application/json');
    echo json_encode(['tipos' => $tipos, 'totais' => $totais]);
    exit;
}

// AJAX: Agendamentos pendentes
if (isset($_GET['ajax']) && $_GET['ajax'] === 'agendamentos') {
    $query = "SELECT id, nome_completo, data_agendamento, telefone, email, fator_RH FROM agendamentos WHERE status = 'Pendente' ORDER BY data_agendamento DESC";
    $result = $conexao->query($query);
    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($dados);
    exit;
}

// AJAX: Doadores em atendimento
if (isset($_GET['ajax']) && $_GET['ajax'] === 'atendimento') {
    $query = "SELECT nome_completo, bi, telefone, email, fator_RH, genero, data, data_agendamento 
              FROM agendamentos 
              WHERE status = 'Confirmado'
              ORDER BY data_agendamento DESC";
    $result = $conexao->query($query);
    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($dados);
    exit;
}

// AJAX: Últimos relatórios clínicos
if (isset($_GET['ajax']) && $_GET['ajax'] === 'ultimas_doacoes') {
    $sql = "SELECT nome_doador, tipo_sanguineo, quantidade, data_doacao, hora_doacao, status_triagem, responsavel_tecnico, local_coleta, observacoes 
            FROM relatorio_clinico 
            ORDER BY data_doacao DESC, hora_doacao DESC 
            LIMIT 10";
    $result = $conexao->query($sql);
    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($dados);
    exit;
}

// AJAX: Próximos agendamentos
if (isset($_GET['ajax']) && $_GET['ajax'] === 'proximos_agendamentos') {
    $amanha = date('Y-m-d', strtotime('+1 day'));
    $query = "SELECT id, nome_completo, data_agendamento, telefone, email, fator_RH 
              FROM agendamentos 
              WHERE data_agendamento >= ? 
              ORDER BY data_agendamento ASC";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $amanha);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($dados);
    exit;
}

// Carregamento inicial para gráfico
$sql = "SELECT fator_RH AS tipo_sanguineo, COUNT(*) AS total 
        FROM agendamentos 
        GROUP BY fator_RH 
        ORDER BY FIELD(fator_RH, 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-')";
$result = $conexao->query($sql);

$tipos = [];
$totais = [];
while ($row = $result->fetch_assoc()) {
    $tipos[] = $row['tipo_sanguineo'];
    $totais[] = (int)$row['total'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Doação de Sangue</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* Paleta de cores e variáveis globais */
:root {
  --primary: #c62828;           /* Cor principal (vermelho) */
  --primary-dark: #b71c1c;      /* Cor principal escura */
  --accent: #ffcdd2;            /* Cor de destaque (vermelho claro) */
  --bg: #fff7f7;                /* Cor de fundo geral */
  --white: #fff;                /* Branco */
  --shadow: 0 2px 8px rgba(0,0,0,0.08); /* Sombra padrão */
  --radius: 12px;               /* Raio de borda padrão */
  --transition: 0.2s;           /* Duração de transições */
}

/* Reset e fonte padrão */
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background: var(--bg);
  display: flex;
  min-height: 100vh;
}

/* Sidebar lateral */
.sidebar {
  width: 240px;
  background: var(--primary);
  height: 100vh;
  padding: 20px;
  display: flex;
  flex-direction: column;
  transition: width var(--transition);
}

/* Links do menu lateral */
.sidebar a {
  color: var(--white);
  text-decoration: none;
  margin: 15px 0;
  display: flex;
  align-items: center;
  font-weight: bold;
  padding: 10px;
  border-radius: var(--radius);
  transition: background var(--transition), transform var(--transition);
  cursor: pointer;
  gap: 10px;
}

/* Efeito hover e ativo no menu lateral */
.sidebar a:hover, .sidebar a.active {
  background: var(--primary-dark);
  transform: translateX(5px) scale(1.04);
}

/* Ícones do menu lateral */
.sidebar i { font-size: 18px; }

/* Área principal do conteúdo */
.main {
  flex: 1;
  padding: 30px;
  background: var(--bg);
  min-height: 100vh;
  transition: padding var(--transition);
}

/* Cabeçalho do painel */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}
.header h1 { font-size: 2rem; }

/* Topbar com perfil e notificações */
.topbar {
  display: flex;
  align-items: center;
  gap: 15px;
}

/* Avatar do usuário */
.profile {
  width: 40px;
  height: 40px;
  background: var(--primary);
  color: var(--white);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 20px;
  box-shadow: var(--shadow);
}

/* Cards de estatísticas */
.cards {
  display: flex;
  gap: 20px;
  margin-bottom: 40px;
  flex-wrap: wrap;
}
.card {
  background: var(--white);
  padding: 24px;
  border-radius: var(--radius);
  flex: 1 1 220px;
  box-shadow: var(--shadow);
  text-align: center;
  transition: box-shadow var(--transition), transform var(--transition);
  min-width: 220px;
}
.card:hover {
  box-shadow: 0 6px 18px rgba(198,40,40,0.13);
  transform: translateY(-4px) scale(1.03);
}
.card i {
  font-size: 32px;
  margin-bottom: 12px;
  color: var(--primary);
  transition: color var(--transition);
}
.card h3 {
  font-size: 1.1rem;
  color: #333;
  margin-bottom: 8px;
}
.card span {
  font-size: 2rem;
  font-weight: bold;
  color: var(--primary);
}

/* Atalhos rápidos */
.shortcuts {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}
.shortcut {
  padding: 20px;
  background: var(--white);
  border-radius: var(--radius);
  border: 1px solid #eee;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  transition: background var(--transition), border-color var(--transition), box-shadow var(--transition);
  box-shadow: var(--shadow);
}
.shortcut:hover {
  background: var(--accent);
  border-color: var(--primary);
  box-shadow: 0 4px 16px rgba(198,40,40,0.10);
}

/* Seção de estatística de coletas */
.coletas-section {
  margin-top: 40px;
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 32px;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  transition: box-shadow var(--transition);
}
.coletas-section h2 {
  color: var(--primary);
  text-align: center;
  margin-bottom: 24px;
}
#grafico-coletas {
  margin: 0 auto;
  display: block;
  max-width: 100%;
  height: auto;
}

/* Controle de visibilidade das seções */
.section { display: none; }
.section.active { display: block; animation: fadeIn 0.5s; }
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px);}
  to { opacity: 1; transform: none;}
}

/* Tabelas responsivas */
.table-responsive {
  width: 100%;
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 18px;
  background: var(--white);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  min-width: 600px;
}
th, td {
  padding: 12px 10px;
  border-bottom: 1px solid #eee;
  text-align: left;
  word-break: break-word;
}
th {
  background: var(--accent);
  color: var(--primary-dark);
  font-weight: bold;
}
tr:hover td {
  background: #fff0f0;
  transition: background var(--transition);
}

/* Inputs e selects */
input, select, textarea {
  width: 100%;
  box-sizing: border-box;
}

/* Responsividade para telas menores */
@media (max-width: 900px) {
  .cards, .shortcuts { flex-direction: column; display: block; }
  .main { padding: 10px; }
  .sidebar { width: 100px; padding: 10px; }
  .sidebar a { font-size: 0.95rem; padding: 8px; }
  .coletas-section { padding: 10px; }
  .table-responsive { min-width: 0; }
  table, th, td { font-size: 0.95rem; }
}
@media (max-width: 700px) {
  .form-triagem-grid {
    grid-template-columns: 1fr;
    padding: 12px;
  }
  .coletas-section { padding: 8px; }
  .main { padding: 5px; }
  table, th, td { font-size: 0.9rem; }
}
@media (max-width: 600px) {
  #menu-toggle { display: block; }
  .sidebar {
    display: none;
    position: fixed;
    top: 0; left: 0; height: 100vh; z-index: 999;
    box-shadow: 2px 0 8px rgba(0,0,0,0.08);
    width: 220px;
    padding: 20px;
  }
  .sidebar.active { display: flex; }
  body.sidebar-open { overflow: hidden; }
  .main { padding: 3px; }
  .coletas-section { padding: 5px; }
  .table-responsive { min-width: 0; }
  table, th, td { font-size: 0.85rem; }
}

/* Grid do formulário de relatório clínico */
.form-relatorio-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px 24px;
  max-width: 700px;
  margin: 30px auto 0 auto;
  background: #fff;
  padding: 24px;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
}
.form-relatorio-grid label {
  font-weight: bold;
  margin-bottom: 6px;
  display: block;
}
.input-relatorio {
  width: 100%;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-top: 4px;
  font-size: 1rem;
  background: #fafafa;
  transition: border-color 0.2s;
}
.input-relatorio:focus {
  border-color: var(--primary);
  outline: none;
  background: #fff;
}
.btn-relatorio {
  background: var(--primary);
  color: #fff;
  padding: 10px 24px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
  font-size: 1rem;
}
.btn-relatorio:hover {
  background: var(--primary-dark);
}

/* Grid do formulário de triagem */
.form-triagem-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px 24px;
}
@media (max-width: 700px) {
  .form-triagem-grid {
    grid-template-columns: 1fr;
    padding: 12px;
  }
}

/* Botões de status da triagem */
.apto-btn-group {
  display: flex;
  gap: 16px;
}
.apto-btn {
  display: inline-block;
  padding: 12px 32px;
  border-radius: 6px;
  background: #f5f5f5;
  border: 2px solid #c62828;
  color: #c62828;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  user-select: none;
  font-size: 1.1rem;
}
input[type="radio"]:checked + .apto-btn {
  background: #c62828;
  color: #fff;
}
.apto-btn:hover {
  background: #ffcdd2;
}

/* Grid de 3 colunas para triagem */
.form-triagem-grid-3 {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 18px 24px;
  max-width: 1000px;
  margin: 30px auto 0 auto;
  background: #fff;
  padding: 24px;
  border-radius: var(--radius);
  box-shadow: var(--shadow);
}
@media (max-width: 900px) {
  .form-triagem-grid-3 {
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 700px) {
  .form-triagem-grid-3 {
    grid-template-columns: 1fr;
    padding: 12px;
  }
}
</style>
</head>
<body>

      <button id="menu-toggle" style="display:none;position:fixed;top:20px;left:20px;z-index:1000;background:#c62828;color:#fff;border:none;padding:10px 14px;border-radius:6px;font-size:22px;cursor:pointer;">
     <i class="fa fa-bars"></i>
   </button>

  <div class="sidebar">
   
    <a href="#" data-section="inicio" class="active"><i class="fa-solid fa-house"></i> Início</a>
    <a href="#" data-section="triagem"><i class="fa-solid fa-notes-medical"></i> Triagem</a>
    <a href="#" data-section="coletas"><i class="fa-solid fa-syringe"></i> Coletas de Sangue</a>
    <a href="#" data-section="agendamentos"><i class="fa-solid fa-calendar-check"></i> Agendamentos</a>
    <a href="#" data-section="atendimento"><i class="fa-solid fa-user-clock"></i> Doador em Atendimento</a>
    <a href="#" data-section="relatorios"><i class="fa-solid fa-file-medical"></i> Relatórios Clínicos</a>
    <a href="#" data-section="configuracoes"><i class="fa-solid fa-gear"></i> Configurações</a>
  </div>

  <div class="main">
    <div id="inicio" class="section active">
      <div class="header">
        <h1>Enfermeiro</h1>
        <div class="topbar">
          <i class="fa-regular fa-bell"></i>
          <div class="profile"><?php echo strtoupper(substr($admin_nome,0,1)); ?></div>
          <span><?php echo htmlspecialchars($admin_nome); ?></span>
          <a href="logout.php" style="margin-left:20px; color:#c62828; font-weight:bold;">Sair</a>
        </div>
      </div>
      <div class="cards">
        <div class="card">
          <i class="fa-solid fa-droplet"></i>
          <h3>Total de Doadores do Dia</h3>
          <span id="doadoresHoje">--</span>
        </div>
        <div class="card">
          <i class="fa-solid fa-vial"></i>
          <h3>Coletas Realizadas Hoje</h3>
          <span id="coletasHoje">--</span>
        </div>
        <div class="card">
          <i class="fa-solid fa-calendar-day"></i>
          <h3>Agendamentos para Amanhã</h3>
          <span id="agendamentosAmanha">--</span>
        </div>
      </div>
      <h2 style="margin-bottom: 15px;">Atalhos Rápidos</h2>
      <div class="shortcuts">
        <div class="shortcut" onclick="window.location.href='triagem.php'"><i class="fa-solid fa-plus"></i> Iniciar nova triagem</div>
        <div class="shortcut" onclick="window.location.href='buscar_doador.php'"><i class="fa-solid fa-file"></i> Ver ficha de doador</div>
        <div class="shortcut" onclick="ativarSecao('proximos_agendamentos')"><i class="fa-solid fa-clock"></i> Ver próximos agendamentos</div>
        <div class="shortcut" onclick="window.location.href='relatorio_diario.php'"><i class="fa-solid fa-chart-line"></i> Gerar relatório clínico diário</div>
      </div>
    </div>

    <div id="triagem" class="section">
      <h2>Triagem</h2>
      <!-- Formulário de Triagem no mesmo formato do formulário de relatório clínico -->
      <form id="form-triagem">
  <div class="form-triagem-grid-3">
    <div>
      <label for="nome">Nome do Doador</label>
      <input type="text" id="nome" name="nome" class="input-relatorio" required>
    </div>
    <div>
      <label for="bi">BI</label>
      <input type="text" id="bi" name="bi" class="input-relatorio" required>
    </div>
    <div>
      <label for="temperatura">Temperatura (°C)</label>
      <input type="number" step="0.1" id="temperatura" name="temperatura" class="input-relatorio" required>
    </div>
    <div>
      <label for="peso">Peso (kg)</label>
      <input type="number" step="0.1" id="peso" name="peso" class="input-relatorio" required>
    </div>
    <div>
      <label for="apto">Status da Triagem</label>
      <select id="apto" name="apto" class="input-relatorio" required>
        <option value="">Selecione</option>
        <option value="Aprovado">Aprovado</option>
        <option value="Reprovado">Reprovado</option>
      </select>
    </div>
    <div>
      <label for="quantidade">Quantidade Doadada (ml)</label>
      <input type="number" id="quantidade" name="quantidade" min="0" class="input-relatorio" required>
    </div>
    <div>
      <label for="hora_doacao">Hora da Doação</label>
      <input type="time" id="hora_doacao" name="hora_doacao" class="input-relatorio" required>
    </div>
    <div>
      <label for="tipo_sanguineo">Tipo Sanguíneo</label>
      <select id="tipo_sanguineo" name="tipo_sanguineo" class="input-relatorio" required>
        <option value="">Selecione</option>
        <option>A+</option><option>A-</option>
        <option>B+</option><option>B-</option>
        <option>AB+</option><option>AB-</option>
        <option>O+</option><option>O-</option>
      </select>
    </div>
    <div>
      <label for="data_doacao">Data da Doação</label>
      <input type="date" id="data_doacao" name="data_doacao" class="input-relatorio" required>
    </div>
    <!-- Última linha: Técnico Responsável, Local da Coleta, Observações -->
    <div>
      <label for="responsavel_tecnico">Técnico Responsável</label>
      <input type="text" id="responsavel_tecnico" name="responsavel_tecnico" class="input-relatorio" required>
    </div>
    <div>
      <label for="local_coleta">Local da Coleta</label>
      <input type="text" id="local_coleta" name="local_coleta" class="input-relatorio" required>
    </div>
    <div>
      <label for="observacoes">Observações</label>
      <textarea id="observacoes" name="observacoes" rows="3" class="input-relatorio"></textarea>
    </div>
  </div>
  <input type="hidden" id="agendamento_id" name="agendamento_id">
  <div style="margin-top:24px;text-align:center;">
    <button type="submit" class="btn-relatorio">Salvar Triagem</button>
  </div>
  <div id="mensagem-triagem" style="text-align:center;margin-top:16px;"></div>
</form>
    </div>
    <div id="coletas" class="section active">
      <div class="coletas-section">
        <h2><i class="fa-solid fa-syringe"></i> Estatística de Coletas por Tipo Sanguíneo</h2>
        <canvas id="grafico-coletas" width="600" height="300"></canvas>
      </div>
    </div>
    <div id="agendamentos" class="section">
      <h2>Agendamentos Pendentes</h2>
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Data Agendamento</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Tipo Sanguíneo</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <!-- Conteúdo preenchido via JS -->
        </tbody>
      </table>
    </div>
    <div id="atendimento" class="section">
      <h2>Doador em Atendimento</h2>
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>BI</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Tipo Sanguíneo</th>
            <th>Gênero</th>
            <th>Data Nasc.</th>
            <th>Data Agendamento</th>
          </tr>
        </thead>
        <tbody>
          <!-- Conteúdo preenchido via JS -->
        </tbody>
      </table>
    </div>
    <div id="relatorios" class="section">
      <h2>Relatórios Clínicos</h2>
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Tipo Sanguíneo</th>
            <th>Quantidade</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Status</th>
            <th>Técnico</th>
            <th>Local</th>
          </tr>
        </thead>
        <tbody>
          <!-- Conteúdo preenchido via JS -->
        </tbody>
      </table>
    </div>
    <div id="configuracoes" class="section">
      <h2>Configurações</h2>
      <!-- Conteúdo das configurações -->
    </div>
    <!-- Próximos Agendamentos -->
<div id="proximos_agendamentos" class="section">
  <h2>Próximos Agendamentos</h2>
  <table>
    <thead>
      <tr>
        <th>Nome</th>
        <th>Data Agendamento</th>
        <th>Telefone</th>
        <th>Email</th>
        <th>Tipo Sanguíneo</th>
      </tr>
    </thead>
    <tbody id="tbody-agendamentos">
      <!-- Conteúdo via JS -->
    </tbody>
  </table>
</div>
  </div>

<script>
/* --------- JS ÚNICO PARA TODA A PÁGINA --------- */

// Atualiza cards do topo
function atualizarCards() {
  fetch('agendamento.php?ajax=total_doadores_hoje')
    .then(res => res.json())
    .then(dados => {
      document.getElementById('doadoresHoje').textContent = dados.total ?? '--';
    })
    .catch(() => {
      document.getElementById('doadoresHoje').textContent = '--';
    });

  fetch('agendamento.php?ajax=coletas_hoje')
    .then(res => res.json())
    .then(dados => {
      document.getElementById('coletasHoje').textContent = dados.total ?? '--';
    })
    .catch(() => {
      document.getElementById('coletasHoje').textContent = '--';
    });

  fetch('agendamento.php?ajax=agendamentos_amanha')
    .then(res => res.json())
    .then(dados => {
      document.getElementById('agendamentosAmanha').textContent = dados.total ?? '--';
    })
    .catch(() => {
      document.getElementById('agendamentosAmanha').textContent = '--';
    });
}

// Atualiza tabela de agendamentos
function atualizarTabelaAgendamentos() {
  fetch('chad.php?ajax=agendamentos')
    .then(res => res.json())
    .then(agendamentos => {
      let html = '';
      agendamentos.forEach(a => {
        html += `<tr data-id="${a.id}">
          <td>${a.nome_completo}</td>
          <td>${a.data_agendamento}</td>
          <td>${a.telefone}</td>
          <td>${a.email}</td>
          <td>${a.fator_RH}</td>
          <td>
            <button class="btn-aceitar" 
              data-id="${a.id}" 
              data-nome="${a.nome_completo}" 
              data-email="${a.email}" 
              data-data="${a.data_agendamento}" 
              data-tipo="${a.fator_RH}">
              Aceitar
            </button>
          </td>
        </tr>`;
      });
      document.querySelector('#agendamentos tbody').innerHTML = html;
    });
}

// Atualiza tabela de atendimento
function atualizarTabelaAtendimento() {
  fetch('chad.php?ajax=atendimento')
    .then(res => res.json())
    .then(atendimentos => {
      let html = '';
      atendimentos.forEach(a => {
        html += `<tr>
          <td>${a.nome_completo}</td>
          <td>${a.bi}</td>
          <td>${a.telefone}</td>
          <td>${a.email}</td>
          <td>${a.fator_RH}</td>
          <td>${a.genero}</td>
          <td>${a.data}</td>
          <td>${a.data_agendamento}</td>
        </tr>`;
      });
      document.querySelector('#atendimento tbody').innerHTML = html;
    });
}

// Atualiza tabela de relatórios clínicos
function atualizarTabelaRelatorios() {
  fetch('chad.php?ajax=ultimas_doacoes')
    .then(res => res.json())
    .then(relatorios => {
      let html = '';
      relatorios.forEach(r => {
        html += `<tr>
          <td>${r.nome_doador}</td>
          <td>${r.tipo_sanguineo}</td>
          <td>${r.quantidade}</td>
          <td>${r.data_doacao}</td>
          <td>${r.hora_doacao}</td>
          <td>${r.status_triagem}</td>
          <td>${r.responsavel_tecnico}</td>
          <td>${r.local_coleta}</td>
        </tr>`;
      });
      document.querySelector('#relatorios tbody').innerHTML = html;
    });
}

// Atualiza gráfico de coletas
let chartColetas = null;
function atualizarGraficoColetas() {
  fetch('chad.php?ajax=tipos_sangue')
    .then(res => res.json())
    .then(dados => {
      if (chartColetas) {
        chartColetas.data.labels = dados.tipos;
        chartColetas.data.datasets[0].data = dados.totais;
        chartColetas.update();
      }
    });
}

// Inicialização ao carregar a página
document.addEventListener('DOMContentLoaded', () => {
  atualizarCards();
  atualizarTabelaAgendamentos();
  atualizarTabelaAtendimento();
  atualizarTabelaRelatorios();

  setInterval(atualizarCards, 10000);
  setInterval(atualizarTabelaAgendamentos, 5000);
  setInterval(atualizarTabelaAtendimento, 5000);
  setInterval(atualizarTabelaRelatorios, 5000);

  // Gráfico de coletas
  const tipos = <?php echo json_encode($tipos); ?>;
  const totais = <?php echo json_encode($totais); ?>;
  const ctx = document.getElementById('grafico-coletas').getContext('2d');
  chartColetas = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: tipos,
      datasets: [{
        label: 'Total de Coletas',
        data: totais,
        backgroundColor: [
          '#FF5722', '#E91E63', '#9C27B0', '#3F51B5',
          '#009688', '#4CAF50', '#FFC107', '#795548'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
  setInterval(atualizarGraficoColetas, 5000);

  // Sidebar responsiva
  const menuBtn = document.getElementById('menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  if (menuBtn && sidebar) {
    menuBtn.addEventListener('click', function() {
      sidebar.classList.toggle('active');
      document.body.classList.toggle('sidebar-open');
    });
    document.querySelectorAll('.sidebar a').forEach(link => {
      link.addEventListener('click', function() {
        if(window.innerWidth <= 600) {
          sidebar.classList.remove('active');
          document.body.classList.remove('sidebar-open');
        }
      });
    });
  }

  // Navegação entre seções
  const sidebarLinks = document.querySelectorAll('.sidebar a');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      sidebarLinks.forEach(l => l.classList.remove('active'));
      this.classList.add('active');
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      const sectionId = this.getAttribute('data-section');
      if (sectionId) {
        const sec = document.getElementById(sectionId);
        if (sec) sec.classList.add('active');
        if (sectionId === 'coletas') {
          setTimeout(() => {
            document.getElementById('grafico-coletas').scrollIntoView({ behavior: 'smooth' });
          }, 100);
        }
      }
    });
  });

  // Salvar triagem via AJAX
  document.getElementById('form-triagem').addEventListener('submit', function(e) {
    if (!document.getElementById('agendamento_id').value) {
      document.getElementById('mensagem-triagem').innerHTML = '<span style="color:red;">Selecione um agendamento válido antes de iniciar a triagem.</span>';
      e.preventDefault();
      return false;
    }
    e.preventDefault();
    const dados = new FormData(this);
    dados.append('salvar_triagem', '1');
    fetch('chad.php', {
      method: 'POST',
      body: dados
    })
    .then(res => res.text())
    .then(text => {
      try {
        const resp = JSON.parse(text);
        document.getElementById('mensagem-triagem').innerHTML = 
          resp.status === 'ok' 
            ? '<span style="color:green;">' + resp.mensagem + '</span>'
            : '<span style="color:red;">' + resp.mensagem + '</span>';
        if (resp.status === 'ok') {
          document.getElementById('form-triagem').reset();
          atualizarTabelaRelatorios();
        }
      } catch(e) {
        document.getElementById('mensagem-triagem').innerHTML = '<span style="color:red;">Erro inesperado: ' + text + '</span>';
      }
    })
    .catch((err) => {
      document.getElementById('mensagem-triagem').innerHTML = '<span style="color:red;">Erro ao salvar triagem: ' + err + '</span>';
    });
  });

  // Evento para aceitar agendamento
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-aceitar')) {
      const btn = e.target;
      const id = btn.dataset.id;
      fetch('chad.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `aceitar_agendamento=1&id=${id}`
      })
      .then(res => res.json())
      .then(resp => {
        if(resp.status === 'ok') {
          btn.closest('tr').remove();
          // Preenche triagem automaticamente
          const d = resp.dados;
          document.querySelector('[data-section="triagem"]').classList.add('active');
document.querySelectorAll('.section').forEach(sec => {
  if(sec.id === 'triagem') sec.classList.add('active');
  else sec.classList.remove('active');
});
          document.getElementById('nome').value = d.nome_completo;
          document.getElementById('bi').value = d.bi;
          document.getElementById('tipo_sanguineo').value = d.fator_RH;
          document.getElementById('data_doacao').value = d.data_agendamento;
          document.getElementById('agendamento_id').value = d.id;
          console.log('Agendamento ID:', document.getElementById('agendamento_id').value);
          document.querySelector('[data-section="triagem"]').scrollIntoView({behavior: 'smooth'});
        }
      });
    }
  });
});

function ativarSecao(secId) {
  document.querySelectorAll('.section').forEach(sec => {
    if(sec.id === secId) sec.classList.add('active');
    else sec.classList.remove('active');
  });
  document.querySelectorAll('.sidebar a').forEach(link => {
    if(link.getAttribute('data-section') === secId) link.classList.add('active');
    else link.classList.remove('active');
  });

  // Chama a função de carregar agendamentos futuros ao ativar a seção correta
  if (secId === 'proximos_agendamentos') {
    carregarProximosAgendamentos();
  }
}

// Carrega próximos agendamentos
function carregarProximosAgendamentos() {
  fetch('chad.php?ajax=proximos_agendamentos')
    .then(res => res.json())
    .then(agendamentos => {
      let html = '';
      agendamentos.forEach(a => {
        html += `<tr>
          <td>${a.nome_completo}</td>
          <td>${a.data_agendamento}</td>
          <td>${a.telefone}</td>
          <td>${a.email}</td>
          <td>${a.fator_RH}</td>
        </tr>`;
      });
      document.getElementById('tbody-agendamentos').innerHTML = html;
    });
}
</script>
</body>
</html>