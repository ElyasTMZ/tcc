<?php
include_once 'php_action/db.php';
include_once 'php_action/funcsenha.php'; // Incluindo as funções de senha


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (autenticarUsuario($email, $senha)) {
        // Login bem-sucedido
        session_start();
        $_SESSION['email'] = $email; // Armazenando o e-mail do usuário na sessão
        header('Location: dashboard.php'); // Redirecionando para a página do dashboard
        exit;
    } else {
        $erro = "E-mail ou senha incorretos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="_css/login.css">
</head>
<body>
    <header>
        <h1>Bem-vindo à Tela de Login</h1>
    </header>
    
    <div class="login-container">
        <h2>Login</h2>
        
        <form action="php_action/login.php" method="post">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required placeholder="Insira seu E-mail">
            
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required placeholder="Insira sua senha">
            
            <button type="submit">Entrar</button>
            
            <a href="recuperar_senha.php" class="link-esqueci-senha">Esqueci minha senha</a>
            <a href="cadastro.php" class="link-cadastrar-se">Cadastrar-se</a>
        </form>
    </div>
    
    <footer>
        <p>© 2024 Sua Empresa. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

<?php include 'footer.php'; ?>
