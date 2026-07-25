<?php

include 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = trim($_POST['nome_completo']);
    $bi = trim($_POST['bi']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data'];
    $fator_rh = $_POST['fator_RH'];
    $genero = $_POST['genero'];
    $data_agendamento = $_POST['data_agendamento'];

    $data_hoje = date("Y-m-d");

    if ($data_nascimento > $data_hoje) {
        echo json_encode(['status'=>'erro','mensagem'=>'A data de nascimento não pode ser no futuro!']);
    } elseif (!preg_match('/^[0-9]{9}$/', $telefone)) {
        echo json_encode(['status'=>'erro','mensagem'=>'O telefone deve conter 9 dígitos.']);
    } elseif (!preg_match('/^[0-9]{12}[A-Za-z]{2}$/', $bi)) {
        echo json_encode(['status'=>'erro','mensagem'=>'BI inválido. Formato correto: 12 números + 2 letras.']);
    } else {
        $verifica = $conexao->prepare("SELECT id FROM agendamentos WHERE bi = ?");
        if (!$verifica) {
            echo json_encode(['status'=>'erro','mensagem'=>'Erro ao preparar a verificação: ' . $conexao->error]);
            exit;
        }
        $verifica->bind_param("s", $bi);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            echo json_encode(['status'=>'erro','mensagem'=>'Este BI já tem um agendamento registrado!']);
        } else {
            $sql = "INSERT INTO agendamentos 
                    (nome_completo, bi, email, telefone, data, fator_RH, genero, data_agendamento) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            if (!$stmt) {
                echo json_encode(['status'=>'erro','mensagem'=>'Erro ao preparar o agendamento: ' . $conexao->error]);
                exit;
            }
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
                echo json_encode(['status'=>'sucesso','mensagem'=>'Agendamento realizado com sucesso!']);
            } else {
                echo json_encode(['status'=>'erro','mensagem'=>'Erro ao agendar: ' . $stmt->error]);
            }
            $stmt->close();
        }
        $verifica->close();
    }
    $conexao->close();
}

?>