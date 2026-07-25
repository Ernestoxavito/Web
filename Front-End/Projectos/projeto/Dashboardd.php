<?php

session_start();
$admin_nome = isset($_SESSION['admin_nome']) ? $_SESSION['admin_nome'] : 'Administrador';
$admin_email = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'admin@seudominio.com';

include 'conexao.php';

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_completo']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $bi = trim($_POST['bi']);
    $senha = trim($_POST['senha']);
    $confirma = trim($_POST['confirma_senha']);
    $nivel = 'Enfermeiro';

// Garante que todos os tipos sanguíneos existam na tabela estoque_sangue
$conexao->query("
    INSERT INTO estoque_sangue (tipo_sanguineo, quantidade) VALUES
        ('A+', 0), ('O+', 0), ('B+', 0), ('AB+', 0),
        ('A-', 0), ('O-', 0), ('B-', 0), ('AB-', 0)
    ON DUPLICATE KEY UPDATE quantidade = quantidade;
");

// Validação simples
    if ($senha !== $confirma) {
        echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
        exit;
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Verifica se o email ou BI já existe
$verifica = $conexao->prepare("SELECT id FROM cadastro WHERE email = ? OR bi = ?");
$verifica->bind_param("ss", $email, $bi);
$verifica->execute();
$verifica->store_result();
if ($verifica->num_rows > 0) {
    echo "<script>alert('Email ou BI já cadastrado!'); window.history.back();</script>";
    exit;
}
$verifica->close();

// Insere o enfermeiro
$stmt = $conexao->prepare("INSERT INTO cadastro (nome_completo, email, telefone, bi, senha, nivel_acesso) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nome, $email, $telefone, $bi, $senha_hash, $nivel);
if ($stmt->execute()) {
    echo "<script>alert('Enfermeiro cadastrado com sucesso!'); window.location.href='Dashboardd.php?secao=enfermeiros';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar enfermeiro!'); window.history.back();</script>";
}
$stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área do Administrador - Doação de Sangue</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff5f5;
            color: #7b241c;
        }
        #sidebar {
            width: 240px;
            background: linear-gradient(180deg, #b71c1c, #f44336 );
            height: 100vh;
            float: left;
            color: white;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
        }
        #sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        #sidebar ul li {
            padding: 16px 22px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #sidebar ul li:hover { background-color: #f44336; }
        #main-content {
            margin-left: 240px;
            padding: 30px;
        }
        .section {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            color: #c62828;
            border-bottom: 2px solid #f8bbd0;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        th {
            background-color: #ffcdd2;
            color: #b71c1c;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .chart-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        canvas {
            max-width: 350px;
            max-height: 180px;
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .subsection { margin-top: 20px; }
        .toggle-btns { margin: 20px 0; }
        .toggle-btns button {
            background-color: #d32f2f;
            border: none;
            color: #fff;
            padding: 8px 16px;
            margin: 0 5px 10px 0;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .toggle-btns button:hover { background-color: #b71c1c; }
        .acoes button {
            margin-right: 5px;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 4px;
        }
        .acoes .ver { background-color: #81c784; color: white; }
        .acoes .editar { background-color: #ffb74d; color: white; }
        .acoes .excluir { background-color: #e57373; color: white; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div id="admin-profile" style="text-align:center; padding: 30px 0 10px 0; border-bottom:1px solid #fff3;">
            <div style="font-size:48px; margin-bottom:8px;">👤</div>
            <div style="font-weight:bold;"><?php echo htmlspecialchars($admin_nome); ?></div>
            <div style="font-size:13px; color:#ffd6d6;"><?php echo htmlspecialchars($admin_email); ?></div>
        </div>
        <ul>
            <li onclick="showSection('dashboard')">📊 Dashboard</li>
            <li onclick="showSection('doadores')">🧟 Doadores</li>
            <li onclick="showSection('cadastro')">🆕  Cadastros</li>
            <li onclick="showSection('agendamento')">🗓️ Agendamento</li>
            <li onclick="showSection('relatorios')">📁 Relatórios</li>
            <li onclick="showSection('ultimas')">🩸 Últimas Doações</li>
            <li onclick="showSection('enfermeiros')">🩺 Enfermeiros</li>
            <li onclick="showSection('feedbacks')">💬 Feedbacks</li>
            <li onclick="logout()">↩️ Sair</li>
        </ul>

          

    </div>

    <!-- Conteúdo principal -->
    <div id="main-content">
        <!-- DASHBOARD -->
        <div id="dashboard" class="section">
            <h2>Dashboard</h2>
            <div class="chart-container">
                <canvas id="doacoesChart"></canvas>
                <canvas id="agendamentosChart"></canvas>
            </div>
            <div class="stock-item" style="margin-bottom: 30px;">
                <h4>Quantidade de Bolsas Disponíveis</h4>
                <canvas id="stockChart"></canvas>
            </div>
        </div>
        

        <!-- ENFERMEIROS -->
        <div id="enfermeiros" class="section">
            <h2>🩺 Cadastro de Enfermeiro - Hospital Geral de Luanda</h2>
            <div class="toggle-btns">
                <button onclick="mostrarFormEnfermeiro()">Novo Enfermeiro</button>
                <button onclick="mostrarTabelaEnfermeiros()">Enfermeiros Cadastrados</button>
            </div>
            <div id="form-enfermeiro" style="display:none;">
                <form id="formEnfermeiro" action="cadastro_enfermeiro.php" method="post" style="background:#fff; padding:15px; border-radius:8px; box-shadow:0 2px 8px #eee; margin-bottom:20px; max-width:500px;">
                    <label>Nome Completo:</label>
                    <input type="text" name="nome_completo" required style="width:100%;margin-bottom:8px;">
                    <label>Email:</label>
                    <input type="email" name="email" required style="width:100%;margin-bottom:8px;">
                    <label>Telefone:</label>
                    <input type="text" name="telefone" required style="width:100%;margin-bottom:8px;">
                    <label>BI:</label>
                    <input type="text" name="bi" required style="width:100%;margin-bottom:8px;">
                    <label>Senha:</label>
                    <input type="password" name="senha" required style="width:100%;margin-bottom:8px;">
                    <label>Confirmar Senha:</label>
                    <input type="password" name="confirma_senha" required style="width:100%;margin-bottom:8px;">
                    <input type="hidden" name="nivel_acesso" value="Enfermeiro">
                    <button type="submit" style="margin-top:10px;">Cadastrar Enfermeiro</button>
                </form>
            </div>
            <div id="tabela-enfermeiros-box" style="display:none;">
                <h3>Enfermeiros Cadastrados</h3>
                <table id="tabela-enfermeiros" style="background:#fff;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>BI</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Preenchido via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CADASTRO -->
        <div id="cadastro" class="section">
            <h2>🆕 Novos Cadastros</h2>
            <table id="tabela-cadastros">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Tipo Sanguíneo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dados preenchidos dinamicamente -->
                </tbody>
            </table>
        </div>

        <!-- DOADORES -->
        <div id="doadores" class="section">
            <h2>Doadores</h2>
            <div class="subsection">
                <h3>✅ Atendidos</h3>
                <table id="tabela-atendidos">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Preenchido dinamicamente -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AGENDAMENTO -->
        <div id="agendamento" class="section">
            <h2>🗓️ Agendamentos</h2>
            <table id="tabela-agendamentos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>BI</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Data Nasc.</th>
                        <th>Fator RH</th>
                        <th>Gênero</th>
                        <th>Data Agendada</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Preenchido dinamicamente -->
                </tbody>
            </table>
        </div>

        <!-- ÚLTIMAS DOAÇÕES -->
    <div id="ultimas" class="section">
        <h2>🩸 Últimas Doações</h2>
        <table id="tabela-ultimas-doacoes">
            <thead>
                <tr>
                    <th>Nome do Doador</th>
                    <th>Tipo Sanguíneo</th>
                    <th>Quantidade (ml)</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Status Triagem</th>
                    <th>Responsável Técnico</th>
                    <th>Local de Coleta</th>
                    <th>Observações</th> <!-- NOVA COLUNA -->
                </tr>
            </thead>
            <tbody>
                <!-- Preenchido via AJAX -->
            </tbody>
        </table>
    </div>

        <!-- FEEDBACKS -->
        <div id="feedbacks" class="section">
            <h2>Feedbacks Recebidos</h2>
            <table id="tabela-feedbacks">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Comentário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dados serão carregados via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- CAIXA DE MENSAGENS INFORME-SE -->
<div id="informese" class="section">
    <h2>📥 Mensagens Informe-se</h2>
    <table id="tabela-informese">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Dúvida</th>
                <th>Data</th>
                <th>Resposta</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Preenchido via AJAX -->
        </tbody>
    </table>
</div>
    </div>

    <!-- Modal de resposta -->
    <div id="modalResposta" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; padding:20px; border-radius:8px; min-width:300px;">
            <h3>Responder Feedback</h3>
            <form id="formResposta">
                <input type="hidden" id="feedbackId" name="feedbackId">
                <textarea id="resposta" name="resposta" rows="4" style="width:100%;" required placeholder="Digite sua resposta"></textarea>
                <br>
                <button type="submit">Enviar</button>
                <button type="button" onclick="fecharModal()">Cancelar</button>
            </form>
        </div>
    </div>

<!-- Modal Visualizar Cadastro -->
<div id="modalVisualizar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:24px; border-radius:8px; min-width:320px; max-width:90vw;">
        <h3>Detalhes do Cadastro</h3>
        <div id="dadosVisualizar"></div>
        <button onclick="fecharModalVisualizar()">Fechar</button>
    </div>
</div>

<!-- Modal Editar Cadastro -->
<div id="modalEditar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:24px; border-radius:8px; min-width:320px; max-width:90vw;">
        <h3>Editar Cadastro</h3>
        <form id="formEditarCadastro">
            <input type="hidden" name="id" id="editId">
            <label>Nome:<br>
                <input type="text" name="nome" id="editNome" required>
            </label><br>
            <label>Email:<br>
                <input type="email" name="email" id="editEmail" required>
            </label><br>
            <label>Telefone:<br>
                <input type="text" name="telefone" id="editTelefone" required>
            </label><br>
            <label>Tipo Sanguíneo:<br>
                <input type="text" name="tipo" id="editTipo" required>
            </label><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="fecharModalEditar()">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal Visualizar Agendamento -->
<div id="modalVisualizarAgendamento" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:24px; border-radius:8px; min-width:320px; max-width:90vw;">
        <h3>Detalhes do Agendamento</h3>
        <div id="dadosVisualizarAgendamento"></div>
        <button onclick="fecharModalVisualizarAgendamento()">Fechar</button>
    </div>
</div>

<!-- Modal Editar Agendamento -->
<div id="modalEditarAgendamento" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;">
    <div style="background:#fff; padding:24px; border-radius:8px; min-width:320px; max-width:90vw;">
        <h3>Editar Agendamento</h3>
        <form id="formEditarAgendamento">
            <input type="hidden" name="id" id="editAgId">
            <label>Nome:<br>
                <input type="text" name="nome" id="editAgNome" required>
            </label><br>
            <label>Data:<br>
                <input type="date" name="data" id="editAgData" required>
            </label><br>
            <label>Horário:<br>
                <input type="time" name="horario" id="editAgHorario" required>
            </label><br>
            <label>Tipo Sanguíneo:<br>
                <input type="text" name="tipo" id="editAgTipo" required>
            </label><br>
            <label>Status:<br>
                <input type="text" name="status" id="editAgStatus" required>
            </label><br><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="fecharModalEditarAgendamento()">Cancelar</button>
        </form>
    </div>
</div>



<script>
    
function showSection(id) {
    document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
    document.getElementById(id).style.display = 'block';
    if(id === 'enfermeiros') {
        carregarEnfermeiros();
    }
    if(id === 'ultimas') {
        carregarUltimasDoacoes();
    }
}

function carregarCadastros() {
    // Novos cadastros 
    $.get('buscar_cadastros.php', function(data) {
        let cadastros = JSON.parse(data);
        let htmlNovos = '';
        cadastros.forEach(function(c) {
            htmlNovos += '<tr>' +
                '<td>' + c.nome_completo + '</td>' +
                '<td>' + c.email + '</td>' +
                '<td>' + c.telefone + '</td>' +
                '<td>' + c.tipo_sanguineo + '</td>' +
                '<td class="acoes">' +
                    '<button class="ver" onclick="visualizarCadastro(' + c.id + ')">👁️</button>' +
                    '<button class="editar" onclick="editarCadastro(' + c.id + ')">✏️</button>' +
                '</td>' +
            '</tr>';
        });
        $('#tabela-cadastros tbody').html(htmlNovos);
    });

    

    // Doadores atendidos 
    $.get('buscar_agendamentos.php', function(data) {
        let agendamentos = JSON.parse(data);
        let htmlAtendidos = '';
        agendamentos.forEach(function(a) {
            htmlAtendidos += '<tr>' +
                '<td>' + a.nome_completo + '</td>' +
                '<td>' + a.data + '</td>' +
                '<td class="acoes">' +
                    '<button class="ver" onclick="visualizarAgendamento(' + a.id + ')">👁️</button>' +
                    '<button class="editar" onclick="editarAgendamento(' + a.id + ')">✏️</button>' +
                    '<button class="excluir" onclick="excluirAgendamento(' + a.id + ')">🗑️</button>' +
                '</td>' +
            '</tr>';
        });
        $('#tabela-atendidos tbody').html(htmlAtendidos);
    });
}
    
    function carregarUltimasDoacoes() {
    $.get('chad.php?ajax=ultimas_doacoes', function(data) {
        let doacoes = typeof data === "object" ? data : JSON.parse(data);
        let html = '';
        doacoes.forEach(function(d) {
            html += '<tr>' +
                '<td>' + d.nome_doador + '</td>' +
                '<td>' + d.tipo_sanguineo + '</td>' +
                '<td>' + d.quantidade + '</td>' +
                '<td>' + d.data_doacao + '</td>' +
                '<td>' + d.hora_doacao + '</td>' +
                '<td>' + d.status_triagem + '</td>' +
                '<td>' + d.responsavel_tecnico + '</td>' +
                '<td>' + d.local_coleta + '</td>' +
                '<td>' + d.observacoes + '</td>' + // NOVA COLUNA
                '</tr>';
        });
        $('#tabela-ultimas-doacoes tbody').html(html);
    });
}

// Carregar agendamentos
function carregarAgendamentos() {
    $.get('buscar_agendamentos.php', function(data) {
        let agendamentos = JSON.parse(data);
        let htmlAgend = '';
        agendamentos.forEach(function(a) {
            htmlAgend += '<tr>' +
                '<td>' + a.nome_completo + '</td>' +
                '<td>' + a.bi + '</td>' +
                '<td>' + a.email + '</td>' +
                '<td>' + a.telefone + '</td>' +
                '<td>' + a.data + '</td>' +
                '<td>' + a.fator_RH + '</td>' +
                '<td>' + a.genero + '</td>' +
                '<td>' + a.data_agendamento + '</td>' +
                '<td class="acoes">' +
                    '<button class="ver" onclick="visualizarAgendamento(' + a.id + ')">👁️</button>' +
                    '<button class="editar" onclick="editarAgendamento(' + a.id + ')">✏️</button>' +
                '</td>' +
            '</tr>';
        });
        $('#tabela-agendamentos tbody').html(htmlAgend);
    });
}

     // Carregar lista de enfermeiros
function carregarEnfermeiros() {
    $.get('buscar_enfermeiros.php', function(data) {
        let enfermeiros = JSON.parse(data);
        let html = '';
        enfermeiros.forEach(function(e) {
            html += '<tr>' +
                '<td>' + e.nome_completo + '</td>' +
                '<td>' + e.email + '</td>' +
                '<td>' + e.telefone + '</td>' +
                '<td>' + e.bi + '</td>' +
                '<td><button onclick="visualizarEnfermeiro(' + e.id + ')">👁️</button></td>' +
            '</tr>';
        });
        $('#tabela-enfermeiros tbody').html(html);
    });
}
    
    // Visualizar enfermeiro (exemplo simples)
function visualizarEnfermeiro(id) {
    $.get('visualizar_enfermeiro.php', { id: id }, function(data) {
        const e = JSON.parse(data);
        alert(`Nome: ${e.nome_completo}\nEmail: ${e.email}\nTelefone: ${e.telefone}\nBI: ${e.bi}`);
    });
}

// Chamar ao abrir a seção
function showSection(id) {
    document.querySelectorAll('.section').forEach(s => s.style.display = 'none');
    document.getElementById(id).style.display = 'block';
    if(id === 'enfermeiros') {
        carregarEnfermeiros();
    }

     if(id === 'ultimas') {
    carregarUltimasDoacoes();
}

}


   function carregarListagem() {
    $.get('buscar_usuarios.php', function(data) {
        let usuarios = JSON.parse(data);
        let html = '';
        usuarios.forEach(function(u) {
            html += '<tr>' +
                '<td>' + u.nome_completo + '</td>' +
                '<td>' + u.email + '</td>' +
                '<td>' + u.telefone + '</td>' +
                '<td>' + u.tipo_sanguineo + '</td>' +
                '<td class="acoes">' +
                    '<button class="ver" onclick="visualizarUsuario(' + u.id + ')">👁️</button>' +
                    // Adicione mais ações se quiser
                '</td>' +
            '</tr>';
        });
        $('#tabela-listagem tbody').html(html);
    });
}


// Visualizar, editar e excluir cadastro
function visualizarCadastro(id) {
    $.get('visualizar_cadastro.php', { id: id }, function(data) {
        const c = JSON.parse(data);
        let html = `
            <b>Nome:</b> ${c.nome_completo}<br>
            <b>Email:</b> ${c.email}<br>
            <b>Telefone:</b> ${c.telefone}<br>
            <b>Tipo Sanguíneo:</b> ${c.tipo_sanguineo}
        `;
        $('#dadosVisualizar').html(html);
        $('#modalVisualizar').css('display', 'flex');
    });
}
function fecharModalVisualizar() {
    $('#modalVisualizar').hide();
}

// Editar com modal e formulário
function editarCadastro(id) {
    $.get('visualizar_cadastro.php', { id: id }, function(data) {
        const c = JSON.parse(data);
        $('#editId').val(id);
        $('#editNome').val(c.nome_completo);
        $('#editEmail').val(c.email);
        $('#editTelefone').val(c.telefone);
        $('#editTipo').val(c.tipo_sanguineo);
        $('#modalEditar').css('display', 'flex');
    });
}
function fecharModalEditar() {
    $('#modalEditar').hide();
}

// Submissão do formulário de edição
$('#formEditarCadastro').on('submit', function(e) {
    e.preventDefault();
    $.post('editar_cadastro.php', {
        id: $('#editId').val(),
        nome: $('#editNome').val(),
        email: $('#editEmail').val(),
        telefone: $('#editTelefone').val(),
        tipo: $('#editTipo').val()
    }, function(res) {
        alert(res);
        fecharModalEditar();
        carregarCadastros();
    });
});

// Visualizar, editar e excluir agendamento
function visualizarAgendamento(id) {
    $.get('visualizar_agendamento.php', { id: id }, function(data) {
        const a = JSON.parse(data);
        let html = `
            <b>Nome:</b> ${a.nome}<br>
            <b>Data:</b> ${a.data}<br>
            <b>Horário:</b> ${a.horario}<br>
            <b>Tipo Sanguíneo:</b> ${a.tipo_sanguineo}<br>
            <b>Status:</b> ${a.status}
        `;
        $('#dadosVisualizarAgendamento').html(html);
        $('#modalVisualizarAgendamento').css('display', 'flex');
    });
}
function fecharModalVisualizarAgendamento() {
    $('#modalVisualizarAgendamento').hide();
}

// Editar agendamento com modal e formulário
function editarAgendamento(id) {
    $.get('visualizar_agendamento.php', { id: id }, function(data) {
        const a = JSON.parse(data);
        $('#editAgId').val(id);
        $('#editAgNome').val(a.nome);
        $('#editAgData').val(a.data);
        $('#editAgHorario').val(a.horario);
        $('#editAgTipo').val(a.tipo_sanguineo);
        $('#editAgStatus').val(a.status);
        $('#modalEditarAgendamento').css('display', 'flex');
    });
}
function fecharModalEditarAgendamento() {
    $('#modalEditarAgendamento').hide();
}

// Submissão do formulário de edição de agendamento
$('#formEditarAgendamento').on('submit', function(e) {
    e.preventDefault();
    $.post('editar_agendamento.php', {
        id: $('#editAgId').val(),
        nome: $('#editAgNome').val(),
        data: $('#editAgData').val(),
        horario: $('#editAgHorario').val(),
        tipo: $('#editAgTipo').val(),
        status: $('#editAgStatus').val()
    }, function(res) {
        alert(res);
        fecharModalEditarAgendamento();
        carregarAgendamentos();
    });
});


    // Carregar feedbacks
function carregarFeedbacks() {
    $.get('buscar_feedbacks.php', function(data) {
        console.log("Feedbacks recebidos:", data); 
        let feedbacks = typeof data === "object" ? data : JSON.parse(data);
        let html = '';
        feedbacks.forEach(function(f) {
            html += '<tr>' +
                '<td>' + (f.nome || 'Anônimo') + '</td>' +
                '<td>' + f.comentario + '</td>' +
                '<td><button onclick="responderFeedback(' + f.id + ')">Responder</button></td>' +
            '</tr>';
        });
        $('#tabela-feedbacks tbody').html(html);
    });
}

// Modal feedback
function responderFeedback(id) {
    $('#feedbackId').val(id);
    $('#modalResposta').css('display', 'flex');
}
function fecharModal() {
    $('#modalResposta').hide();
}
$('#formResposta').on('submit', function(e) {
    e.preventDefault();
    $.post('responder_feedback.php', {
        id: $('#feedbackId').val(),
        resposta: $('#resposta').val()
    }, function(res) {
        alert(res);
        fecharModal();
    });
});

// Logout
function logout() {
    if (confirm("Deseja realmente sair?")) {
        window.location.href = 'logout.php';
    }
}

// Gráfico de Estoque de Sangue (Quantidade de Bolsas Disponíveis)
let stockChart; // Variável global para o gráfico

function carregarGraficoEstoque() {
    $.get('buscar_estoque_grafico.php', function(res) {
        let estoque = typeof res === "object" ? res : JSON.parse(res);
        const ctxStock = document.getElementById('stockChart').getContext('2d');
        if (stockChart) {
            stockChart.data.labels = estoque.labels;
            stockChart.data.datasets[0].data = estoque.data;
            stockChart.update();
        } else {
            stockChart = new Chart(ctxStock, {
                type: 'bar',
                data: {
                    labels: estoque.labels,
                    datasets: [{
                        label: 'Bolsas Disponíveis',
                        data: estoque.data,
                        backgroundColor: [
                            '#FF5722', '#E91E63', '#9C27B0', '#3F51B5',
                            '#009688', '#4CAF50', '#FFC107', '#795548'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    });
}


      function mostrarTabela(tipo) {
    $('#tabela-doadores, #tabela-agendamentos, #tabela-bancos').hide();
    if (tipo === 'doadores') {
        $('#tabela-doadores').show();
        carregarDoadores();
    } else if (tipo === 'agendamentos') {
        $('#tabela-agendamentos').show();
        carregarAgendamentosListagem();
    } else if (tipo === 'bancos') {
        $('#tabela-bancos').show();
        carregarBancos();
    }
}

function carregarDoadores() {
    $.get('buscar_doadores.php', function(data) {
        let doadores = JSON.parse(data);
        let html = '';
        doadores.forEach(function(d) {
            html += '<tr>' +
                '<td>' + d.nome_completo + '</td>' +
                '<td>' + d.email + '</td>' +
                '<td>' + d.telefone + '</td>' +
                '<td>' + d.tipo_sanguineo + '</td>' +
                '</tr>';
        });
        $('#tabela-doadores tbody').html(html);
    });
}

function carregarAgendamentosListagem() {
    $.get('buscar_agendamentos.php', function(data) {
        let agendamentos = JSON.parse(data);
        let html = '';
        agendamentos.forEach(function(a) {
            html += '<tr>' +
                '<td>' + a.nome_completo + '</td>' +
                '<td>' + a.bi + '</td>' +
                '<td>' + a.email + '</td>' +
                '<td>' + a.telefone + '</td>' +
                '<td>' + a.data + '</td>' +
                '<td>' + a.fator_RH + '</td>' +
                '<td>' + a.genero + '</td>' +
                '<td>' + a.data_agendamento + '</td>' +
                '</tr>';
        });
        $('#tabela-agendamentos tbody').html(html);
    });
}

function carregarBancos() {
    $.get('buscar_bancos.php', function(data) {
        let bancos = JSON.parse(data);
        let html = '';
        bancos.forEach(function(b) {
            html += '<tr>' +
                '<td>' + b.nome + '</td>' +
                '<td>' + b.localizacao + '</td>' +
                '<td>' + b.horario_funcionamento + '</td>' +
                '</tr>';
        });
        $('#tabela-bancos tbody').html(html);
    });
}

// Atualizar total de doadores hoje
function atualizarTotalDoadoresHoje() {
    fetch('agendamento.php?ajax=total_doadores_hoje')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalDoadoresHoje').textContent = data.total;
        })
        .catch(() => {
            document.getElementById('totalDoadoresHoje').textContent = '--';
        });
}

// Inicialização
window.onload = function () {
    showSection('dashboard');
    carregarCadastros();
    carregarAgendamentos();
    carregarFeedbacks();
    carregarUltimasDoacoes();
    carregarGraficoEstoque(); // Chama o gráfico ao carregar

    atualizarTotalDoadoresHoje();
    setInterval(atualizarTotalDoadoresHoje, 5000);

    setInterval(carregarCadastros, 5000);
    setInterval(carregarAgendamentos, 5000);
   setInterval(carregarFeedbacks, 5000);
    setInterval(carregarGraficoEstoque, 5000); // Atualiza o gráfico a cada 5s
    
   

    // Gráficos de doações e agendamentos
    const ctx1 = document.getElementById('doacoesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Novos', 'Recorrentes'],
            datasets: [{
                data: [70, 30],
                backgroundColor: ['#d32f2f', '#f8bbd0'],
                borderWidth: 1
            }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { color: '#7b241c' } } } }
    });

    const ctx2 = document.getElementById('agendamentosChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'],
            datasets: [{
                label: 'Agendamentos',
                data: [30, 45, 50, 70, 60],
                fill: false,
                borderColor: '#d32f2f',
                backgroundColor: '#ef9a9a',
                tension: 0.4
            }]
        },
        options: {
            plugins: { legend: { labels: { color: '#7b241c' } } },
            scales: {
                x: { ticks: { color: '#a93226' } },
                y: { beginAtZero: true, ticks: { color: '#a93226' } }
            }
        }
    });
};

function mostrarFormEnfermeiro() {
    document.getElementById('form-enfermeiro').style.display = 'block';
    document.getElementById('tabela-enfermeiros-box').style.display = 'none';
}
function mostrarTabelaEnfermeiros() {
    document.getElementById('form-enfermeiro').style.display = 'none';
    document.getElementById('tabela-enfermeiros-box').style.display = 'block';
    carregarEnfermeiros();
}
// Opcional: mostrar tabela por padrão ao abrir a seção
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('enfermeiros')) {
        mostrarTabelaEnfermeiros();
    }
});

function carregarInformese() {
    $.get('buscar_informese.php', function(data) {
        let mensagens = typeof data === "object" ? data : JSON.parse(data);
        let html = '';
        mensagens.forEach(function(m) {
            html += '<tr>' +
                '<td>' + m.nome + '</td>' +
                '<td>' + m.email + '</td>' +
                '<td>' + m.duvida + '</td>' +
                '<td>' + m.data_envio + '</td>' +
                '<td>' + (m.resposta ? m.resposta : '<span style="color:gray;">Sem resposta</span>') + '</td>' +
                '<td>' +
                    (m.resposta ? '' : '<button onclick="responderInformese(' + m.id + ')">Responder</button>') +
                '</td>' +
            '</tr>';
        });
        $('#tabela-informese tbody').html(html);
    });
}

// Modal para responder
function responderInformese(id) {
    let resposta = prompt("Digite a resposta para esta dúvida:");
    if (resposta && resposta.trim() !== '') {
        $.post('responder_informese.php', {id: id, resposta: resposta}, function(ret) {
            alert(ret);
            carregarInformese();
        });
    }
}

// Chame carregarInformese() ao abrir a seção
// E coloque setInterval(carregarInformese, 5000); para atualizar em tempo real

function carregarRespostasUsuario(email) {
    fetch('buscar_respostas_usuario.php?email=' + encodeURIComponent(email))
    .then(res => res.json())
    .then(respostas => {
        let html = '';
        respostas.forEach(r => {
            html += `<li><b>Sua dúvida:</b> ${r.duvida}<br><b>Resposta:</b> ${r.resposta ? r.resposta : '<i>Aguardando resposta</i>'}</li>`;
        });
        document.getElementById('lista-respostas').innerHTML = html;
    });
}

// Chame carregarRespostasUsuario(email) após o envio do formulário, usando o e-mail informado
</script>
</body>
</html>