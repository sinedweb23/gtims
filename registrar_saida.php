<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}

// Incluir arquivo de configuração do banco de dados
include 'config.php';

// Obter o nome do usuário a partir do email na sessão
$email = $_SESSION['email'];
$sql_usuario = "SELECT Nome FROM usuario WHERE Email = '$email'";
$result_usuario = $conn->query($sql_usuario);

if ($result_usuario->num_rows > 0) {
    $row_usuario = $result_usuario->fetch_assoc();
    $responsavel = $row_usuario['Nome'];
} else {
    // Se não encontrar o usuário, defina um valor padrão para o responsável
    $responsavel = 'Desconhecido';
}

// Processar o formulário quando for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se todos os campos obrigatórios estão preenchidos
    if (isset($_POST['produto'], $_POST['quantidade'], $_POST['setor'], $_POST['data_saida'])) {
        // Obter os dados do formulário
        $produto_id = $_POST['produto'];
        $quantidade = $_POST['quantidade'];
        $setor_id = $_POST['setor'];
        $data_saida = $_POST['data_saida'];

        // Atualizar o estoque do produto
        $sql_atualizar_estoque = "UPDATE produto SET Estoque = Estoque - $quantidade WHERE ProdutoID = $produto_id";
        if ($conn->query($sql_atualizar_estoque) === TRUE) {
            // Inserir um novo registro na tabela movimentacao com o responsável
            $sql_movimentacao = "INSERT INTO movimentacao (TipoMovimentacao, ProdutoID, Quantidade, Data, Responsavel, SetorID) 
                                VALUES ('Saida', $produto_id, $quantidade, '$data_saida', '$responsavel', $setor_id)";

            if ($conn->query($sql_movimentacao) === TRUE) {
                echo "<p>Saída registrada com sucesso.</p>";
            } else {
                echo "Erro ao registrar saída: " . $conn->error;
            }
        } else {
            echo "Erro ao atualizar estoque: " . $conn->error;
        }
    } else {
        echo "<p>Todos os campos são obrigatórios.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Saída de Estoque - Sistema de Gestão de TI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registrar Saída de Estoque</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    Produto:
    <select name="produto">
        <option value="">Selecione...</option>
        <?php
        // Consultar o banco de dados para obter os produtos
        $sql = "SELECT ProdutoID, CONCAT(NomeProduto, ' - ', NumeroSerie) AS ProdutoInfo FROM produto";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['ProdutoID'] . "'>" . $row['ProdutoInfo'] . "</option>";
            }
        } else {
            echo "<option value=''>Nenhum produto encontrado</option>";
        }
        ?>
    </select>


        <br><br>
        
        Quantidade:
        <input type="number" name="quantidade" id="quantidade" required>
        <br><br>

        Setor:
        <select name="setor">
            <option value="">Selecione...</option>
            <?php
            // Consultar o banco de dados para obter os setores
            $sql = "SELECT SetorID, Setor FROM setor";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['SetorID'] . "'>" . $row['Setor'] . "</option>";

                }
            } else {
                echo "<option value=''>Nenhum setor encontrado</option>";
            }
            ?>
        </select>
        <br><br>
        
        Data da Saída:
        <input type="date" name="data_saida" id="data_saida" required>
        <br><br>
        
        <button type="submit">Registrar Saída</button>
    </form>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
