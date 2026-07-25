<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_completo'] ?? '';
    $bi = $_POST['bi'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data_nascimento = $_POST['data'] ?? '';
    $fator_rh = $_POST['fator_RH'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $data_agendamento = $_POST['data_agendamento'] ?? '';

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Comprovante de Agendamento',0,1,'C');
    $pdf->SetFont('Arial','',12);
    $pdf->Ln(10);
    $pdf->Cell(0,10,"Nome: $nome",0,1);
    $pdf->Cell(0,10,"BI: $bi",0,1);
    $pdf->Cell(0,10,"Email: $email",0,1);
    $pdf->Cell(0,10,"Telefone: $telefone",0,1);
    $pdf->Cell(0,10,"Data de Nascimento: $data_nascimento",0,1);
    $pdf->Cell(0,10,"Fator RH: $fator_rh",0,1);
    $pdf->Cell(0,10,"Genero: $genero",0,1);
    $pdf->Cell(0,10,"Data de Agendamento: $data_agendamento",0,1);

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="comprovante_agendamento.pdf"');
    $pdf->Output('D', 'comprovante_agendamento.pdf');
    exit;
}
?>