<?php
session_start();
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados
include_once 'php_action/funcsenha.php'; // Inclui as funções relacionadas à senha

// Verifica se o usuário logado é um administrador
if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'admin') {
    header('Location: dashboard.php'); // Redireciona para o dashboard se não for admin
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telCelular = trim($_POST['telCelular']);
    $senha = $_POST['senha'];
    $tipoUsuario = $_POST['tipoUsuario']; // Recebe o tipo de usuário (funcionario ou admin)

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($email) && !empty($telCelular) && !empty($senha) && !empty($tipoUsuario)) {
        // Gera o salt e cria o hash da senha
        $salt = gerarSalt();
        $hashSenha = criarHashSenha($senha, $salt);

        try {
            // SQL para inserir dados na tabela tbFuncionarios
            $sql = "INSERT INTO tbFuncionarios (nome, email, telCelular, senha, salt, tipoUsuario) 
                    VALUES (:nome, :email, :telCelular, :senha, :salt, :tipoUsuario)";
            
            // Preparar a declaração
            $stmt = $pdo->prepare($sql);
            
            // Bind dos parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telCelular', $telCelular);
            $stmt->bindParam(':senha', $hashSenha);
            $stmt->bindParam(':salt', $salt);
            $stmt->bindParam(':tipoUsuario', $tipoUsuario);

            // Executar a declaração
            $stmt->execute();
            
            $mensagem = "Funcionário cadastrado com sucesso!";
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar o funcionário: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" href="_css/cadastro_funcionario.css">
</head>
<body>
    <header>
        <h1>Cadastrar Novo Funcionário</h1>
        <nav>
            <a href="dashboard.php">Voltar para o Dashboard</a>
        </nav>
    </header>

    <main>
        <form action="cadastro_funcionario.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="telCelular">Telefone Celular:</label>
            <input type="tel" id="telCelular" name="telCelular" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="tipoUsuario">Tipo de Usuário:</label>
            <select id="tipoUsuario" name="tipoUsuario" required>
                <option value="funcionario">Funcionário</option>
                <option value="admin">Administrador</option>
            </select>

            <button type="submit">Cadastrar Funcionário</button>
        </form>

        <?php if (isset($erro)): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <?php if (isset($mensagem)): ?>
            <p style="color: green;"><?php echo $mensagem; ?></p>
        <?php endif; ?>
    </main>
</body>
</html>
<?php include 'footer.php'; ?>