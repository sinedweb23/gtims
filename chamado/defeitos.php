<?php
// Inclui o arquivo de configuração do banco de dados
require_once('../chamado/config1.php');

// Inicializa a variável para armazenar a mensagem
$mensagem = '';

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar_defeito'])) {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $prioridade = $_POST['prioridade'];

    // Insere os dados do defeito no banco de dados
    $sql = "INSERT INTO defeitos (nome, prioridade) VALUES ('$nome', '$prioridade')";
    if ($conn->query($sql) === TRUE) {
        // Define a mensagem de sucesso
        $mensagem = "Defeito adicionado com sucesso!";
    } else {
        // Se houver um erro, exibe uma mensagem de erro
        $mensagem = "Erro ao adicionar o defeito: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Defeito</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Adicionar Defeito</h2>
        <!-- Exibe a mensagem -->
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem == "Defeito adicionado com sucesso!" ? "success" : "danger"; ?>" role="alert">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nome">Nome do Defeito:</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prioridade">Prioridade:</label>
                <select name="prioridade" id="prioridade" class="form-control" required>
                    <option value="baixo">Baixo</option>
                    <option value="medio">Médio</option>
                    <option value="alto">Alto</option>
                </select>
            </div>
            <button type="submit" name="adicionar_defeito" class="btn btn-primary">Adicionar Defeito</button>
        </form>
    </div>
</body>
</html>
