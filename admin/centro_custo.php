<?php
include '../config.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão de TI</title>
</head>
<body>
    <h1>Sistema de Gestão de TI</h1>
    
    <!-- Painel Administrativo -->
    <section>
        <h2>Painel Administrativo</h2>
        <!-- Aqui você pode adicionar qualquer conteúdo relacionado ao painel administrativo -->
        <!-- Por exemplo, links para outras funcionalidades -->
        <ul>
            <li><a href="#centro_custos">Gestão de Centro de Custos</a></li>
            <li><a href="#orcamento">Orçamento da Área</a></li>
            <li><a href="#despesas">Lançamento de Despesas</a></li>
        </ul>
    </section>
    
    <!-- Gestão de Centro de Custos -->
    <section id="centro_custos">
        <h2>Gestão de Centro de Custos</h2>
        <!-- Aqui você pode adicionar formulários para cadastrar e listar centros de custos -->
        <!-- Exemplo de formulário para cadastrar um novo centro de custos -->
        <form action="adicionar_centro_custos.php" method="post">
            <label for="nome">Nome do Centro de Custos:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="responsavel">Responsável:</label>
            <input type="text" id="responsavel" name="responsavel">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"></textarea>
            <button type="submit">Adicionar Centro de Custos</button>
        </form>
        <!-- Exemplo de listagem de centros de custos -->
        <?php
        $sql_centro_custos = "SELECT * FROM centro_custos";
        $result_centro_custos = $conn->query($sql_centro_custos);
        if ($result_centro_custos->num_rows > 0) {
            echo "<ul>";
            while($row = $result_centro_custos->fetch_assoc()) {
                echo "<li>" . $row["nome"] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "Nenhum centro de custos encontrado.";
        }
        ?>
    </section>
    
    <!-- Orçamento da Área -->
    <section id="orcamento">
        <h2>Orçamento da Área</h2>
        <!-- Aqui você pode adicionar formulários para definir e acompanhar o orçamento -->
        <!-- Exemplo de formulário para definir o orçamento -->
        <form action="definir_orcamento.php" method="post">
            <label for="id_centro_custos">Selecione o Centro de Custos:</label>
            <select id="id_centro_custos" name="id_centro_custos" required>
                <?php
                $sql_centro_custos_dropdown = "SELECT id, nome FROM centro_custos";
                $result_centro_custos_dropdown = $conn->query($sql_centro_custos_dropdown);
                if ($result_centro_custos_dropdown->num_rows > 0) {
                    while($row = $result_centro_custos_dropdown->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum centro de custos encontrado</option>";
                }
                ?>
            </select>
            <label for="orcamento">Orçamento:</label>
            <input type="text" id="orcamento" name="orcamento" required>
            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio">
            <label for="data_fim">Data de Término:</label>
            <input type="date" id="data_fim" name="data_fim">
            <button type="submit">Definir Orçamento</button>
        </form>
    </section>
    
    <!-- Lançamento de Despesas -->
    <section id="despesas">
        <h2>Lançamento de Despesas</h2>
        <!-- Aqui você pode adicionar formulários para registrar despesas -->
        <!-- Exemplo de formulário para registrar uma nova despesa -->
        <form action="registrar_despesa.php" method="post">
            <label for="id_centro_custos_despesa">Selecione o Centro de Custos:</label>
            <select id="id_centro_custos_despesa" name="id_centro_custos_despesa" required>
                <?php
                $sql_centro_custos_dropdown_despesa = "SELECT id, nome FROM centro_custos";
                $result_centro_custos_dropdown_despesa = $conn->query($sql_centro_custos_dropdown_despesa);
                if ($result_centro_custos_dropdown_despesa->num_rows > 0) {
                    while($row = $result_centro_custos_dropdown_despesa->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum centro de custos encontrado</option>";
                }
                ?>
            </select>
            <label for="descricao_despesa">Descrição da Despesa:</label>
            <input type="text" id="descricao_despesa" name="descricao_despesa" required>
            <label for="valor_despesa">Valor:</label>
            <input type="text" id="valor_despesa" name="valor_despesa" required>
            <label for="data_despesa">Data:</label>
            <input type="date" id="data_despesa" name="data_despesa" required>
            <button type="submit">Registrar Despesa</button>
        </form>
    </section>
    
</body>
</html>
