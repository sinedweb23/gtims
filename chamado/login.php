<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config1.php');

// Inicia a sessão
session_start();

// Verifica se o usuário já está autenticado, se sim, redireciona para o Painel Admin
if(isset($_SESSION['admin_id'])) {
    header("Location: admin_panel.php");
    exit;
}

// Verifica se os dados do formulário foram enviados
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    
    // Consulta o banco de dados para verificar as credenciais
$sql = "SELECT id FROM usuarios WHERE nome = '$usuario' AND senha = '$senha'";
$result = $conn->query($sql);


    // Verifica se encontrou algum registro com as credenciais fornecidas
    if ($result->num_rows > 0) {
        // Autenticação bem-sucedida, cria uma sessão para o usuário
        $_SESSION['admin_id'] = $result->fetch_assoc()['id'];
        
        // Redireciona para o Painel Admin
        header("Location: admin_panel.php");
        exit;
    } else {
        // Credenciais inválidas, exibe uma mensagem de erro
        $erro_login = "Usuário ou senha incorretos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Admin</title>
    <!-- Link para o arquivo CSS (se necessário) -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login - Painel Admin</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <?php if(isset($erro_login)) { ?>
                <div class="alert alert-danger"><?php echo $erro_login; ?></div>
            <?php } ?>
            <div class="form-group">
                <button type="submit">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
