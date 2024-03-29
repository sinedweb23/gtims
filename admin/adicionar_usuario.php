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
    </body>
</html>