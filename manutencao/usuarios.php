<?php
session_start();
include('config.php'); // Inclua a conexão com o banco de dados

if ($_SESSION['permissao'] != 'admin') {
    header("Location: erro_permissao.php");
    exit;
}

$query = "SELECT * FROM usuarios";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Usuários</h2>
        <a href="cadastrar_usuario.php" class="btn btn-success mb-3">Cadastrar Usuário</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>Email</th>
                    <th>Permissão</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['nome']; ?></td>
                        <td><?php echo $user['sobrenome']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['permissao']; ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="excluir_usuario.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
