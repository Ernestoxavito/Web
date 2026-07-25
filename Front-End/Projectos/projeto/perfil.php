<?php
include 'conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Consulta os dados do usuário no banco
$sql = "SELECT nome_completo, email, telefone, endereco FROM Cadastro WHERE id = ?";
$stmt = $conexao->prepare($sql);

if (!$stmt) {
    die("Erro na preparação da consulta: " . $conexao->error);
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Erro na execução da consulta: " . $stmt->error);
}

$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("Usuário não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: #fff5f5;
            font-family: 'Segoe UI', sans-serif;
            color: #7b241c;
        }
        .perfil-container {
            max-width: 500px;
            margin: 60px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px #e57373;
            padding: 32px 28px 24px 28px;
        }
        .perfil-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 24px;
        }
        .perfil-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #ffcdd2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #c62828;
            font-weight: bold;
        }
        .perfil-nome {
            font-size: 1.5rem;
            font-weight: bold;
            color: #c62828;
        }
        .form-group label {
            color: #b71c1c;
            font-weight: 500;
        }
        .btn-editar, .btn-voltar {
            background: #c62828;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.2s;
            margin-right: 8px;
        }
        .btn-editar:hover, .btn-voltar:hover {
            background: #b71c1c;
        }
        .btn-salvar {
            background: #388e3c;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-salvar:hover {
            background: #256029;
        }
        .btn-sair {
            background: #888;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 28px;
            font-size: 1rem;
            font-weight: 500;
            margin-left: 10px;
        }
        @media (max-width: 600px) {
            .perfil-container { padding: 18px 6px; }
            .perfil-header { flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>
    <div class="perfil-container">
        <div class="perfil-header">
            <div class="perfil-avatar">
                <?= strtoupper(substr($usuario['nome_completo'], 0, 1)) ?>
            </div>
            <div>
                <div class="perfil-nome"><?= htmlspecialchars($usuario['nome_completo'] ?? 'Não informado') ?></div>
                <div style="color:#a93226; font-size:1rem;"><?= htmlspecialchars($usuario['email'] ?? '') ?></div>
            </div>
        </div>
        <!-- Exibição dos dados -->
        <div id="dadosPerfil">
            <table class="table">
                <tr>
                    <th>Nome</th>
                    <td><?= htmlspecialchars($usuario['nome_completo'] ?? 'Não informado') ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($usuario['email'] ?? 'Não informado') ?></td>
                </tr>
                <tr>
                    <th>Telefone</th>
                    <td><?= htmlspecialchars($usuario['telefone'] ?? 'Não informado') ?></td>
                </tr>
                <tr>
                    <th>Endereço</th>
                    <td><?= htmlspecialchars($usuario['endereco'] ?? 'Não informado') ?></td>
                </tr>
            </table>
            <div style="text-align:right;">
                <button class="btn-editar" id="btnEditar">Editar Perfil</button>
                <a href="index.php"><button type="button" class="btn-voltar">Voltar</button></a>
                <a href="logout.php"><button type="button" class="btn-sair">Sair</button></a>
            </div>
        </div>
        <!-- Formulário de edição (inicialmente escondido) -->
        <form id="formPerfil" style="display:none;">
            <div class="form-group">
                <label for="nome_completo">Nome</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($usuario['nome_completo']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>">
            </div>
            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($usuario['endereco']) ?>">
            </div>
            <div style="text-align:right;">
                <button type="submit" class="btn-salvar">Salvar</button>
                <button type="button" class="btn-voltar" id="btnCancelar">Cancelar</button>
            </div>
        </form>
        <div id="msgPerfil" style="margin-top:15px;"></div>
    </div>
    <script>
    // Mostrar formulário de edição
    $('#btnEditar').on('click', function() {
        $('#dadosPerfil').hide();
        $('#formPerfil').show();
        $('#msgPerfil').html('');
    });
    // Cancelar edição
    $('#btnCancelar').on('click', function() {
        $('#formPerfil').hide();
        $('#dadosPerfil').show();
        $('#msgPerfil').html('');
    });
    // Salvar edição via AJAX
    $('#formPerfil').on('submit', function(e) {
        e.preventDefault();
        $.post('atualizar_perfil.php', $(this).serialize(), function(res) {
            $('#msgPerfil').html('<div class="alert alert-success">Perfil atualizado com sucesso! Recarregando...</div>');
            setTimeout(function(){ location.reload(); }, 1200);
        }).fail(function(xhr) {
            $('#msgPerfil').html('<div class="alert alert-danger">Erro ao atualizar perfil.</div>');
        });
    });
    </script>
</body>
</html>