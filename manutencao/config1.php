<?php
// Parâmetros de conexão com o banco de dados
$servername = "localhost";
$username = "suporte";
$password = "Msul.2024"; // Senha em branco
$database = "manutencao";

// Tenta realizar a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Configura o charset para UTF-8 para evitar problemas com caracteres especiais
$conn->set_charset("utf8");
?>
