<?php

$dbservidor = 'localhost';
$dbusuario = 'root';
$dbsenha = '';
$dbname = 'Gestao_Doacoes';

$conexao = new mysqli($dbservidor, $dbusuario, $dbsenha, $dbname);

if ($conexao->connect_error) {
    die('Falha na conexão: ' . $conexao->connect_error);
}
?> 