<?php
session_start();
include('config1.php');

if ($_SESSION['permissao'] != 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $repetir_senha = $_POST['repetir_senha'];
    $permissao = $_POST['permissao'];

    // Verificar se o email já está cadastrado
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email já cadastrado.";
    } else {
        if ($senha == $repetir_senha) {
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
            $query = "INSERT INTO usuarios (nome, sobrenome, email, senha, permissao) VALUES ('$nome', '$sobrenome', '$email', '$senha_hash', '$permissao')";
            if (mysqli_query($conn, $query)) {
                header("Location: usuarios.php");
                exit;
            } else {
                $error = "Erro ao cadastrar usuário.";
            }
        } else {
            $error = "As senhas não coincidem.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Cadastrar Usuário</h2>
        <form method="post" action="cadastrar_usuario.php">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="sobrenome">Sobrenome:</label>
                <input type="text" class="form-control" id="sobrenome" name="sobrenome" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="repetir_senha">Repetir Senha:</label>
                <input type="password" class="form-control" id="repetir_senha" name="repetir_senha" required>
            </div>
            <div class="form-group">
                <label for="permissao">Permissão:</label>
                <select class="form-control" id="permissao" name="permissao" required>
                    <option value="admin">Admin</option>
                    <option value="usuario">Usuário</option>
                </select>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>
</html>
