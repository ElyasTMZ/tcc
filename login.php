<?php
session_start();
include_once 'php_action/db.php';
include_once 'php_action/funcsenha.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (!empty($email) && !empty($senha)) {
        // Verifica se o usuário existe e autentica a senha
        if (autenticarUsuario($email, $senha)) {
            // Busca os dados do usuário incluindo telefone
            $sql = "SELECT codUsu, nome, email, telCelular, tipoUsuario FROM tbUsuarios WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Armazena as informações do usuário na sessão
                $_SESSION['codUsu'] = $usuario['codUsu'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['telCelular'] = $usuario['telCelular'];
                $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];

                // Verifica o tipo de usuário
                if ($usuario['tipoUsuario'] === 'admin') {
                    // Usuário é um administrador
                    header('Location: dashboard.php'); // Redireciona para a dashboard
                } else {
                    // Usuário é um cliente comum
                    header('Location: menu.php'); // Redireciona para o menu do cliente
                }
                exit();
            } else {
                $erro = "Usuário não encontrado.";
            }
        } else {
            $erro = "Credenciais inválidas.";
        }
    } else {
        $erro = "Preencha todos os campos.";
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
        <h1>Login</h1>
    </header>
    
    <div class="form-container">
        <div class="login-container">
            <h2>Faça seu login</h2>

            <?php
            // Exibe a mensagem de erro, se houver
            if (!empty($erro)) {
                echo "<p style='color:red;'>$erro</p>";
            }

            // Exibe uma mensagem de sucesso após o cadastro
            if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
                echo "<p style='color:green;'>Cadastro realizado com sucesso! Faça o login.</p>";
            }
            ?>

            <form action="login.php" method="post">
                <!-- Campo de e-mail -->
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required placeholder="Insira seu E-mail">
                
                <!-- Campo de senha -->
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required placeholder="Insira sua senha">
                
                <!-- Botão de login -->
                <button type="submit">Entrar</button>
                
                <!-- Links para cadastro -->
                <div class="links">
                    <span>Não tem uma conta? </span>
                    <a href="cadastro.php">Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
