<?php
include 'db_connect.php';

// Código para cadastrar ativos, salas e andares
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cadastrar_ativo'])) {
        $nome = $_POST['nome'];
        $sql = "INSERT INTO ativos (nome) VALUES (:nome)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nome' => $nome]);
    } elseif (isset($_POST['cadastrar_andar'])) {
        $nome = $_POST['nome'];
        $sql = "INSERT INTO andares (nome) VALUES (:nome)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nome' => $nome]);
    } elseif (isset($_POST['cadastrar_sala'])) {
        $nome = $_POST['nome'];
        $andar_id = $_POST['andar_id'];
        $sql = "INSERT INTO salas (nome, andar_id) VALUES (:nome, :andar_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['nome' => $nome, 'andar_id' => $andar_id]);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Cadastro de Ativos</title>
</head>
<body>
    <h1>Painel Admin</h1>
    <form method="POST">
        <h2>Cadastrar Ativo</h2>
        Nome: <input type="text" name="nome" required>
        <button type="submit" name="cadastrar_ativo">Cadastrar</button>
    </form>
    <form method="POST">
        <h2>Cadastrar Andar</h2>
        Nome: <input type="text" name="nome" required>
        <button type="submit" name="cadastrar_andar">Cadastrar</button>
    </form>
    <form method="POST">
        <h2>Cadastrar Sala</h2>
        Nome: <input type="text" name="nome" required>
        Andar: 
        <select name="andar_id" required>
            <?php
            $stmt = $conn->query("SELECT * FROM andares");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="cadastrar_sala">Cadastrar</button>
    </form>
</body>
</html>
