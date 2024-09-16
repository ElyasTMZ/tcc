<?php
include_once 'php_action/db.php';
include_once 'php_action/funcsenha.php'; // Incluindo as funções de senha

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telCelular = $_POST['telCelular'];
    $senha = $_POST['senha'];

    if (registrarUsuario($nome, $email, $telCelular, $senha)) {
        header('Location: login.php'); // Redireciona para a página de login após cadastro
    } else {
        $erro = "Erro ao registrar o usuário";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="_css/cadastro.css">
</head>
<body>
    <header>
        <h1>Cadastro</h1>
    </header>
    
    <div class="form-container">
        <div class="cadastro-container">
            <h2>Cadastre-se</h2>
            
            <form action="php_action/cadastro.php" method="post">
                <!-- Campo de e-mail -->
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required placeholder="Insira seu E-mail">
                
                <!-- Campo de nome -->
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required placeholder="Insira seu nome">
                
                <!-- Campo de telefone -->
                <label for="telCelular">Telefone Celular:</label>
                <input type="text" id="telCelular" name="telCelular" required placeholder="Insira seu telefone celular">
                
                <!-- Campo de senha -->
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required placeholder="Insira sua senha">
                
                <!-- Botão de cadastrar -->
                <button type="submit">Cadastrar</button>
                
                <!-- Links para login -->
                <div class="links">
                    <span>Já possui uma conta? </span>
                    <a href="login.php">Faça login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


