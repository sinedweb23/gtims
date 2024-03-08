<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta SQL para verificar se o usuário existe
    $sql = "SELECT * FROM usuario WHERE Email = '$email' AND Senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuário autenticado, redirecionar para a página inicial
        $_SESSION['email'] = $email; // Armazenar o email do usuário na sessão
        header("Location: index.php");
        exit();
    } else {
        // Usuário não encontrado, exibir mensagem de erro
        echo "Email ou senha incorretos.";
    }
}

$conn->close();
?>
