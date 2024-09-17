<?php
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados
include_once 'php_action/funcsenha.php'; // Inclui as funções relacionadas às senhas

$erro = ''; // Variável para armazenar mensagens de erro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telCelular = trim($_POST['telCelular']);
    $senha = $_POST['senha'];

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($email) && !empty($telCelular) && !empty($senha)) {
        // Tenta registrar o usuário
        if (registrarUsuario($nome, $email, $telCelular, $senha)) {
            // Redireciona para a página de login após o cadastro bem-sucedido
            header('Location: login.php?sucesso=1');
            exit();
        } else {
            $erro = "Erro ao registrar o usuário. Verifique os dados e tente novamente.";
        }
    } else {
        $erro = "Por favor, preencha todos os campos necessários.";
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

            <?php
            // Exibe a mensagem de erro, se houver
            if (!empty($erro)) {
                echo "<p style='color:red;'>$erro</p>";
            }
            ?>

            <form action="cadastro.php" method="post">
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
<?php include 'footer.php'; ?>