<?php
session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gestao_ti');

// Conexão com o banco de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica se há erros na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}


?>
