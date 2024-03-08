<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .banner {
            width: 100%;
            position: fixed; /* Banner fixo */
            top: 0; /* Posicionamento no topo */
            text-align: center;
            background-color: #ffffff; /* Cor de fundo do banner */
            padding: 10px 0; /* Espaçamento interno */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra */
        }

        .banner img {
            max-width: 100%; /* Evita que a imagem ultrapasse a largura do banner */
            height: auto;
        }

        .login-form {
            width: 300px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 80px; /* Espaçamento para evitar sobreposição com o banner */
        }

        .login-form h1 {
            text-align: center;
        }

        .login-form label {
            display: block;
            margin-bottom: 10px;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="banner">
    <img src="banner.png" alt="Banner" style="max-width: 10%; height: auto;">

    </div>
    <div class="login-form">
        <h1>Login</h1>
        <form action="autenticar.php" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
