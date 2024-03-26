<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta SQL usando declaração preparada para evitar injeção SQL
    $sql = "SELECT Senha, Permissao FROM usuario WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hash_senha = $row['Senha'];
        $permissao = $row['Permissao'];

        // Verifica se a senha fornecida corresponde ao hash armazenado usando password_verify
        if (password_verify($senha, $hash_senha)) {
            // Senha correta, autenticado com sucesso
            $_SESSION['email'] = $email;
            $_SESSION['permissao'] = $permissao; // Armazena a permissão do usuário na sessão
            header("Location: index.php"); // Redireciona para a página inicial
            exit();
        } else {
            // Senha incorreta
            echo "Email ou senha incorretos.";
        }
    } else {
        // Usuário não encontrado
        echo "Email ou senha incorretos.";
    }

    $stmt->close();
}

$conn->close();
?>
