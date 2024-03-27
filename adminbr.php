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
if ($_SESSION['permissao'] !== 2 && $_SESSION['permissao'] !== 2 && $_SESSION['permissao'] !== 3) {
    // Se a permissão não for 1 (usuário normal), 2 (admin) ou 3 (super-admin), redirecionar para página de acesso não autorizado
    header("Location: acesso_nao_autorizado.php");
    exit();
}

// Adicionar novo usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $permissao = $_POST['permissao']; // Captura o valor da permissão selecionada

    $sql_add_user = "INSERT INTO usuario (Nome, Email, Senha, permissao) VALUES ('$nome', '$email', '$senha', '$permissao')";
    if ($conn->query($sql_add_user) === TRUE) {
        echo "<p>Novo usuário adicionado com sucesso.</p>";
    } else {
        echo "Erro ao adicionar novo usuário: " . $conn->error;
    }
}

// Consulta SQL para obter as permissões da tabela permissoes
$sql_permissoes = "SELECT * FROM permissoes";
$result_permissoes = $conn->query($sql_permissoes);

// Cadastrar nova categoria
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $categoria = $_POST['categoria'];

    $sql_add_category = "INSERT INTO categoria (Categoria) VALUES ('$categoria')";
    if ($conn->query($sql_add_category) === TRUE) {
        echo "<p>Nova categoria cadastrada com sucesso.</p>";
    } else {
        echo "Erro ao cadastrar nova categoria: " . $conn->error;
    }
}

// Cadastrar novo local
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_location'])) {
    $setor = $_POST['setor'];

    $sql_add_location = "INSERT INTO setor (Setor) VALUES ('$setor')";
    if ($conn->query($sql_add_location) === TRUE) {
        echo "<p>Novo local cadastrado com sucesso.</p>";
    } else {
        echo "Erro ao cadastrar novo local: " . $conn->error;
    }
}

// Cadastrar novo switch
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_switch'])) {
    $localSwitch = $_POST['local_switch'];
    $numeroSerie = $_POST['numero_serie'];
    $ip = $_POST['ip'];
    $modelo = $_POST['modelo'];

    $sql_add_switch = "INSERT INTO switch (Local_Switch, Numero_Serie, Ip, Modelo) VALUES ('$localSwitch', '$numeroSerie', '$ip', '$modelo')";
    if ($conn->query($sql_add_switch) === TRUE) {
        echo "<p>Novo switch cadastrado com sucesso.</p>";
    } else {
        echo "Erro ao cadastrar novo switch: " . $conn->error;
    }
}

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
            margin: 20px
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
        input[type="password"] {
            width: 250px;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Administração</h1>

    <h2>Adicionar Novo Usuário</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <!-- Adiciona um campo de seleção para a permissão -->
        <label for="permissao">Permissão:</label>
        <select id="permissao" name="permissao" required>
            <?php
            // Loop para exibir as opções de permissões
            while ($row_permissoes = $result_permissoes->fetch_assoc()) {
                echo "<option value='{$row_permissoes['id']}'>{$row_permissoes['tipo']}</option>";
            }
            ?>
        </select>

        <input type="submit" name="add_user" value="Adicionar Usuário">
    </form>

    <h2>Cadastrar Nova Categoria</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="categoria">Categoria:</label>
        <input type="text" id="categoria" name="categoria" required>
        <input type="submit" name="add_category" value="Cadastrar Categoria">
    </form>

    <h2>Cadastrar Novo Local</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="setor">Setor:</label>
        <input type="text" id="setor" name="setor" required>
        <input type="submit" name="add_location" value="Cadastrar Local">
    </form>

    <h2>Adicionar Novo Switch</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="local_switch">Local Switch:</label>
        <input type="text" id="local_switch" name="local_switch" required>
        <label for="numero_serie">Número de Série:</label>
        <input type="text" id="numero_serie" name="numero_serie" required>
        <label for="ip">IP:</label>
        <input type="text" id="ip" name="ip" required>
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" required>
        <input type="submit" name="add_switch" value="Adicionar Switch">
    </form>

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
