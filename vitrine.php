<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desapegos de TI Morumbi Sul</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .banner {
            max-width: 50%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .produtos {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }

        .produto {
            width: calc(33.33% - 20px);
            margin-bottom: 40px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .produto img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .produto h2 {
            margin-bottom: 10px;
        }

        .produto .valor {
            font-size: 2.2em;
            color: #ff5733; /* Cor destacada */
        }

        .produto p {
            margin-bottom: 5px;
        }

        @media screen and (max-width: 768px) {
            .produto {
                width: calc(50% - 20px);
            }
        }

        @media screen and (max-width: 480px) {
            .produto {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="banner.png" alt="Banner" class="banner">
        <h1>Desapegos de TI Morumbi Sul</h1>
        <p>Explore nossa seleção de equipamentos usados.</p>
        <p>Por favor, note que todos os equipamentos são vendidos sem garantia, mas garantimos que cada item foi cuidadosamente inspecionado para garantir sua funcionalidade e qualidade. Sinta-se à vontade para navegar em nossa vitrine e encontrar o equipamento perfeito para você.<br><br>
            Formas de pagamento: Cartão, dinheiro, pix em desconto e folha.
        </p><BR>
        
        <p style="color: font-size: 16px;">Interessados, ir até a sala do suporte.</p><br>
        <p style="color: red; font-size: 16px;">NOVIDADES EM DESTAQUE</p>
        <div class="produtos">
            <?php
                require_once 'config.php'; // Arquivo de conexão com o banco de dados
                
                // Consulta SQL para buscar os produtos em ordem inversa
                $sql = "SELECT nome, valor, observacao, link_img FROM produtos ORDER BY id DESC";
                $result = $conn->query($sql);
                
                // Verifica se há produtos
                if ($result->num_rows > 0) {
                    // Exibir os produtos em blocos
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='produto'>";
                        echo "<img src='".$row['link_img']."' alt='".$row['nome']."'>";
                        echo "<h2>".$row['nome']."</h2>";
                        echo "<p><strong>Valor:</strong> <span class='valor'>R$ ".$row['valor']."</span></p>";
                        echo "<p><strong>Observação:</strong> ".$row['observacao']."</p>";
                        echo "</div>";
                    }
                } else {
                    echo "Nenhum produto encontrado.";
                }
                $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
