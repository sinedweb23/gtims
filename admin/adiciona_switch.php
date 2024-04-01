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
    </body>
</html>