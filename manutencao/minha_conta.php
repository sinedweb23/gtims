<?php
session_start();
include('config1.php'); // Inclua a conexão com o banco de dados

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $repetir_nova_senha = $_POST['repetir_nova_senha'];

    // Verificar a senha atual
    $query = "SELECT senha FROM usuarios WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (password_verify($senha_atual, $user['senha'])) {
        if ($nova_senha == $repetir_nova_senha) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
            $query = "UPDATE usuarios SET senha = '$nova_senha_hash' WHERE id = $id";
            if (mysqli_query($conn, $query)) {
                $success = "Senha atualizada com sucesso.";
            } else {
                $error = "Erro ao atualizar a senha.";
            }
        } else {
            $error = "As novas senhas não coincidem.";
        }
    } else {
        $error = "A senha atual está incorreta.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Minha Conta</h2>
        <form method="post" action="minha_conta.php">
            <div class="form-group">
                <label for="senha_atual">Senha Atual:</label>
                <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
            </div>
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="form-group">
                <label for="repetir_nova_senha">Repetir Nova Senha:</label>
                <input type="password" class="form-control" id="repetir_nova_senha" name="repetir_nova_senha" required>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Atualizar Senha</button>
        </form>
    </div>
</body>
</html>
