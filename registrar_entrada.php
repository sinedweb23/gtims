<?php

session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    // Se não estiver logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada de Estoque - Sistema de Gestão de TI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registrar Entrada de Estoque</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        Produto Existente:
        <select name="produto_existente" onchange="toggleCamposNovoProduto()">
            <option value="">00 Produto Novo...</option>
            <?php
            include 'config.php';

            $sql = "SELECT ProdutoID, NomeProduto, NumeroSerie, Estoque FROM produto";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['ProdutoID'] . "-" . $row['NomeProduto'] . "-" . $row['NumeroSerie'] . "-" . $row['Estoque'] . "'>" . $row['NomeProduto'] . " - " . $row['NumeroSerie'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum produto encontrado</option>";
            }

            $conn->close();
            ?>
        </select>
        <br><br>
        
        <div id="campos_novo_produto">
            Novo Produto:
            <input type="text" name="novo_produto" disabled>
            <br><br>
            
            Categoria:
            <select name="categoria" id="categoria" onchange="updateCategoriaID()" disabled>
                <option value="">Selecione...</option>
                <?php
                include 'config.php';

                $sql = "SELECT CategoriaID, Categoria FROM categoria";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['CategoriaID'] . "'>" . $row['Categoria'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma categoria encontrada</option>";
                }

                $conn->close();
                ?>
            </select>
            <input type="hidden" name="categoria_id" id="categoria_id">
            <br><br>
            
            Quantidade:
            <input type="number" name="quantidade" id="quantidade" disabled>
            <br><br>
            
            Valor:
            <input type="text" name="valor" id="valor" disabled>
            <br><br>
            
            Data da Entrada:
            <input type="date" name="data_entrada" id="data_entrada" disabled>
            <br><br>
            
            Número de Série:
            <input type="text" name="numero_serie" id="numero_serie">
            <br><br>
            
            Validade da Garantia:
            <input type="date" name="validade_garantia" id="validade_garantia" disabled>
            <br><br>
            
            Link da DANFE:
            <input type="text" name="link_danfe" id="link_danfe" disabled>
            <br><br>
            
            Link da Imagem:
            <input type="text" name="link_imagem" id="link_imagem" disabled>
            <br><br>
            
            Origem:
            <input type="text" name="origem" id="origem" disabled>
            <br><br>
        </div>
        
        <button type="submit">Registrar Entrada</button>
    </form>

    <script>
    function toggleCamposNovoProduto() {
        var produtoExistenteSelect = document.getElementsByName("produto_existente")[0];
        var camposNovoProduto = document.getElementById("campos_novo_produto").getElementsByTagName("input");
        var camposBloqueados = ['novo_produto', 'validade_garantia', 'link_danfe', 'link_imagem'];

        if (produtoExistenteSelect.value !== "") {
            for (var i = 0; i < camposNovoProduto.length; i++) {
                if (camposBloqueados.includes(camposNovoProduto[i].name)) {
                    camposNovoProduto[i].disabled = true;
                } else {
                    camposNovoProduto[i].disabled = false;
                }
            }
            // Desativa o campo 'Número de Série'
            document.getElementById("numero_serie").disabled = true;
        } else {
            for (var i = 0; i < camposNovoProduto.length; i++) {
                camposNovoProduto[i].disabled = false;
            }
            // Ativa o campo 'Número de Série'
            document.getElementById("numero_serie").disabled = false;
            // Ativa o campo 'Categoria'
            document.getElementById("categoria").disabled = false;
        }
    }

    function updateCategoriaID() {
        var categoriaSelect = document.getElementById("categoria");
        var categoriaIDInput = document.getElementById("categoria_id");
        categoriaIDInput.value = categoriaSelect.value;
    }
</script>



<?php


// Incluir arquivo de configuração do banco de dados
include 'config.php';

// Obter o nome do usuário a partir do email na sessão
$email = $_SESSION['email'];
$sql_usuario = "SELECT Nome FROM Usuario WHERE Email = '$email'";
$result_usuario = $conn->query($sql_usuario);

if ($result_usuario->num_rows > 0) {
    $row_usuario = $result_usuario->fetch_assoc();
    $responsavel = $row_usuario['Nome'];
} else {
    // Se não encontrar o usuário, defina um valor padrão para o responsável
    $responsavel = 'Desconhecido';
}

// Verificar se foi submetido um formulário de entrada de estoque
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['produto_existente']) && !empty($_POST['produto_existente'])) {
        // Se um produto existente foi selecionado
        $dados_produto = explode("-", $_POST['produto_existente']);
        $produto_id = $dados_produto[0];
        $estoque_atual = isset($dados_produto[3]) ? intval($dados_produto[3]) : 0; // Estoque atual do produto
        $quantidade = $_POST['quantidade'];

        // Soma a quantidade atual com a quantidade a ser adicionada
        $novo_estoque = $estoque_atual + $quantidade;

        // Atualiza o estoque do produto no banco de dados
        $sql_atualiza_estoque = "UPDATE produto SET Estoque = $novo_estoque WHERE ProdutoID = $produto_id";

        if ($conn->query($sql_atualiza_estoque) === TRUE) {
            // Insira na tabela movimentacao
            $valor = $_POST['valor'];
            $data_entrada = $_POST['data_entrada'];
            $origem = $_POST['origem'];

            // Inserir na tabela movimentacao com o responsável
            $sql_movimentacao = "INSERT INTO movimentacao (TipoMovimentacao, ProdutoID, Quantidade, Valor, Data, Origem, Responsavel) 
                                VALUES ('Entrada', $produto_id, $quantidade, '$valor', '$data_entrada', '$origem', '$responsavel')";

            if ($conn->query($sql_movimentacao) === TRUE) {
                echo "<p>Estoque atualizado e movimentação registrada com sucesso.</p>";
            } else {
                echo "Erro ao registrar movimentação: " . $conn->error;
            }
        } else {
            echo "Erro ao atualizar o estoque: " . $conn->error;
        }
    } else {
        // Se nenhum produto existente foi selecionado, então estamos inserindo um novo produto
        $novo_produto = $_POST['novo_produto'];
        $categoria_id = $_POST['categoria_id'];
        $quantidade = $_POST['quantidade'];
        $valor = $_POST['valor'];
        $data_entrada = $_POST['data_entrada'];
        $numero_serie = $_POST['numero_serie'];
        $validade_garantia = $_POST['validade_garantia'];
        $link_danfe = $_POST['link_danfe'];
        $link_imagem = $_POST['link_imagem'];
        $origem = $_POST['origem'];

        // Inserir o novo produto no banco de dados
        $sql_produto = "INSERT INTO produto (NomeProduto, CategoriaID, NumeroSerie, ValidadeGarantia, LinkDanfe, LinkImagem, Estoque, Valor) 
                        VALUES ('$novo_produto', $categoria_id, '$numero_serie', '$validade_garantia', '$link_danfe', '$link_imagem', $quantidade, '$valor')";

        if ($conn->query($sql_produto) === TRUE) {
            $last_id = $conn->insert_id;

            // Inserir na tabela movimentacao com o responsável
            $sql_movimentacao = "INSERT INTO movimentacao (TipoMovimentacao, ProdutoID, Quantidade, Valor, Data, Origem, Responsavel) 
                                VALUES ('Entrada', $last_id, $quantidade, '$valor', '$data_entrada', '$origem', '$responsavel')";

            if ($conn->query($sql_movimentacao) === TRUE) {
                echo "<p>Registro de entrada criado com sucesso.</p>";
            } else {
                echo "Erro ao criar registro de entrada: " . $conn->error;
            }
        } else {
            echo "Erro ao cadastrar novo produto: " . $conn->error;
        }
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>

</body>
</html>
