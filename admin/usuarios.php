<?php
include '../config.php';


session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}

// Verificar a permissão do usuário
if ($_SESSION['permissao'] !== 2 && $_SESSION['permissao'] !== 2 && $_SESSION['permissao'] !== 3) {
    // Se a permissão não for 1 (usuário normal), 2 (admin) ou 3 (super-admin), redirecionar para página de acesso não autorizado
    header("Location: acesso_nao_autorizado.php");
    exit();
}

// Consulta SQL para obter as permissões da tabela permissoes
$sql_permissoes = "SELECT * FROM permissoes";
$result_permissoes = $conn->query($sql_permissoes);

// Excluir usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql_delete_user = "DELETE FROM usuario WHERE UsuarioID = '$user_id'";
    if ($conn->query($sql_delete_user) === TRUE) {
        echo "<p>Usuário excluído com sucesso.</p>";
    } else {
        echo "Erro ao excluir usuário: " . $conn->error;
    }
}

// Redefinir senha
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $user_id = $_POST['user_id'];
    $nova_senha_hash = password_hash('mudar@123', PASSWORD_DEFAULT);
    $sql_reset_password = "UPDATE usuario SET Senha = '$nova_senha_hash' WHERE UsuarioID = '$user_id'";
    if ($conn->query($sql_reset_password) === TRUE) {
        echo "<p>Senha redefinida com sucesso.</p>";
    } else {
        echo "Erro ao redefinir senha: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Admin</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h1 {
    color: #333;
}

form {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 250px;
    padding: 5px;
    margin-bottom: 10px;
}

input[type="submit"],
button {
    padding: 8px 15px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
}

input[type="submit"]:hover,
button:hover {
    background-color: #0056b3;
}

p {
    color: green;
}

.error {
    color: red;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ddd;
    padding: 8px;
}

th {
    background-color: #f2f2f2;
}

th, td {
    text-align: left;
}

    </style>
</head>
<body>

<h2>Lista de Usuários</h2>
    <table>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Permissão</th>
            <th>Ações</th>
        </tr>
        <?php
        // Consulta SQL para obter os usuários (excluindo aqueles com permissão 3)
        $sql_users = "SELECT * FROM usuario WHERE permissao != 3";
        $result_users = $conn->query($sql_users);

        if ($result_users->num_rows > 0) {
            while ($row = $result_users->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Nome"] . "</td>";
                echo "<td>" . $row["Email"] . "</td>";
                echo "<td>" . $row["permissao"] . "</td>";
                echo "<td>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                echo "<input type='hidden' name='user_id' value='" .
                $row["UsuarioID"] . "'>";
                echo "<input type='submit' name='delete_user' value='Excluir'>";
                echo "<input type='submit' name='reset_password' value='Redefinir Senha'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum usuário encontrado.</td></tr>";
        }
        ?>
    </table>
</body>
</html>