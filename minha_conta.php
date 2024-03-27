<?php
include 'config.php';

session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}

// Verificar a permissão do usuário
if ($_SESSION['permissao'] !== 1 && $_SESSION['permissao'] !== 2 && $_SESSION['permissao'] !== 3) {
    // Se a permissão não for 1 (usuário normal), 2 (admin) ou 3 (super-admin), redirecionar para página de acesso não autorizado
    header("Location: acesso_nao_autorizado.php");
    exit();
}

// Obter o email do usuário atualmente logado
$email_usuario = $_SESSION['email'];

// Verificar se o formulário de alteração de senha foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_nova_senha = $_POST['confirmar_nova_senha'];

    // Verificar se a senha atual está correta
    $sql = "SELECT Senha FROM usuario WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($senha_hash);
        $stmt->fetch();

        if (password_verify($senha_atual, $senha_hash)) {
            // Senha atual está correta, verificar se as novas senhas coincidem
            if ($nova_senha === $confirmar_nova_senha) {
                // Atualizar a senha no banco de dados
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET Senha = ? WHERE Email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $nova_senha_hash, $email_usuario);
                $stmt->execute();
                $mensagem = "Senha atualizada com sucesso.";
            } else {
                $erro = "As novas senhas não coincidem.";
            }
        } else {
            $erro = "Senha atual incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta</title>
    <!-- Adicionar o link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos adicionais personalizados podem ser adicionados aqui */
        body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Bem-vindo, <?php echo $email_usuario; ?></h1>
        <?php if (isset($mensagem)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($erro)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mt-4">
            <div class="form-group">
                <label for="senha_atual">Senha atual:</label>
                <input type="password" id="senha_atual" name="senha_atual" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nova_senha">Nova senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirmar_nova_senha">Confirmar nova senha:</label>
                <input type="password" id="confirmar_nova_senha" name="confirmar_nova_senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Alterar Senha</button>
        </form>
        <br>
        <a href="logout.php" class="btn btn-secondary">Sair</a>
    </div>

    <!-- Adicionar o link para o Bootstrap JS (opcional, apenas se você precisar de componentes JS) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
