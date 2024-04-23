<?php
// Inclui o arquivo de configuração do banco de dados
require_once('config.php');

// Inicializa a variável para armazenar a mensagem
$mensagem = '';

// Verifica se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['abrir_chamado'])) {
    // Obtém os dados do formulário
    $nome_solicitante = $_POST['nome_solicitante']; // Adiciona a variável para o nome do solicitante
    $id_setor = $_POST['id_setor']; // Alterado para 'id_setor' para corresponder à nova tabela
    $id_defeito = $_POST['id_defeito'];
    $observacao = mysqli_real_escape_string($conn, $_POST['observacao']);

    // Insere os dados do chamado no banco de dados, incluindo o nome do solicitante
    $sql = "INSERT INTO gestao_ti (nome, id_setor, id_defeito, observacao) VALUES ('$nome_solicitante', '$id_setor', '$id_defeito', '$observacao')";
    if ($conn->query($sql) === TRUE) {
        // Define a mensagem de sucesso
        $mensagem = "Chamado aberto com sucesso!";
    } else {
        // Se houver um erro, exibe uma mensagem de erro
        $mensagem = "Erro ao abrir o chamado: " . $conn->error;
    }
}

// Consulta o banco de dados para obter os setores
$sql_setores = "SELECT SetorID, Setor FROM setor"; // Alterado para corresponder à nova tabela
$result_setores = $conn->query($sql_setores);

// Consulta o banco de dados para obter os defeitos
$sql_defeitos = "SELECT id, nome FROM defeitos";
$result_defeitos = $conn->query($sql_defeitos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Chamado</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilo para o logo responsivo -->
    <style>
        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            max-width: 100%; /* Para tornar o logo responsivo */
            height: auto; /* Para manter a proporção */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Adiciona o logo no topo -->
        <img src="banner.png" alt="Logo" class="logo">
        <h2 class="mb-4">Abertura de Chamado</h2>
        <!-- Exibe a mensagem -->
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem == "Chamado aberto com sucesso!" ? "success" : "danger"; ?>" role="alert">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nome_solicitante">Nome do Solicitante:</label>
                <input type="text" name="nome_solicitante" id="nome_solicitante" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="id_setor">Setor:</label> <!-- Alterado para corresponder à nova tabela -->
                <select name="id_setor" id="id_setor" class="form-control" required>
                    <option value="">Selecione o setor</option>
                    <?php
                    // Exibe as opções de setores
                    if ($result_setores->num_rows > 0) {
                        while($row = $result_setores->fetch_assoc()) {
                            echo "<option value='".$row["SetorID"]."'>".$row["Setor"]."</option>"; // Alterado para corresponder à nova tabela
                        }
                    } else {
                        echo "<option value=''>Nenhum setor encontrado</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_defeito">Defeito:</label>
                <select name="id_defeito" id="id_defeito" class="form-control" required>
                    <option value="">Selecione o defeito</option>
                    <?php
                    // Exibe as opções de defeitos
                    if ($result_defeitos->num_rows > 0) {
                        while($row = $result_defeitos->fetch_assoc()) {
                            echo "<option value='".$row["id"]."'>".$row["nome"]."</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum defeito encontrado</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="observacao">Observação:</label>
                <textarea name="observacao" id="observacao" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" name="abrir_chamado" class="btn btn-primary">Abrir Chamado</button>
        </form>
    </div>
</body>
</html>
