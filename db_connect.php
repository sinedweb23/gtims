<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname_gestao = "gestao_ti";
$dbname_chamado = "chamado"; // Nome do banco de dados onde as tabelas salas e andares estão

try {
    // Conexão com o banco de dados gestao_ti
    $conn_gestao = new PDO("mysql:host=$servername;dbname=$dbname_gestao", $username, $password);
    $conn_gestao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conexão com o banco de dados chamado
    $conn_chamado = new PDO("mysql:host=$servername;dbname=$dbname_chamado", $username, $password);
    $conn_chamado->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
