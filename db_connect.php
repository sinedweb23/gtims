<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "suporte";
$password = "Msul.2024#";
$dbname = "gestao_ti"; // Substitua 'nome_do_banco' pelo nome do seu banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

echo "Conexão bem-sucedida!";
?>
