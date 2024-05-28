<?php
include('config1.php'); // Inclua a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $repetir_senha = $_POST['repetir_senha'];
    $permissao = $_POST['permissao'];

    if ($senha == $repetir_senha) {
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
        $query = "INSERT INTO usuarios (nome, sobrenome, email, senha, permissao) VALUES ('$nome', '$sobrenome', '$email', '$senha_hash', '$permissao')";
        if (mysqli_query($conn, $query)) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    } else {
        echo "As senhas não coincidem.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Temporário de Usuário</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Registro Temporário de Usuário</h2>
        <form method="post" action="registro_temp.php">
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
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>
</html>
