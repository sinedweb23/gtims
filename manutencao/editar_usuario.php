<?php
session_start();
include('config1.php');

if ($_SESSION['permissao'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM usuarios WHERE id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $permissao = $_POST['permissao'];

    $query = "UPDATE usuarios SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', permissao = '$permissao' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: usuarios.php");
    } else {
        $error = "Erro ao atualizar usuário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Editar Usuário</h2>
        <form method="post" action="editar_usuario.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $user['nome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="sobrenome">Sobrenome:</label>
                <input type="text" class="form-control" id="sobrenome" name="sobrenome" value="<?php echo $user['sobrenome']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="permissao">Permissão:</label>
                <select class="form-control" id="permissao" name="permissao" required>
                    <option value="admin" <?php if ($user['permissao'] == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="usuario" <?php if ($user['permissao'] == 'usuario') echo 'selected'; ?>>Usuário</option>
                </select>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
</body>
</html>
