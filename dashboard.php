<?php
session_start();
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Busca informações do usuário logado
$emailUsuario = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT nome FROM tbUsuarios WHERE email = :email");
$stmt->bindParam(':email', $emailUsuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário é funcionário ou cliente
$tipoUsuario = $_SESSION['tipoUsuario']; // Verificar se essa variável é definida corretamente durante o login

// Busca resumo das vendas (se o usuário for funcionário)
if ($tipoUsuario === 'funcionario') {
    $stmtVendas = $pdo->query("SELECT COUNT(*) AS totalVendas, SUM(quantidade) AS totalItensVendidos FROM tbVendas");
    $resumoVendas = $stmtVendas->fetch(PDO::FETCH_ASSOC);

    // Busca produtos com baixa quantidade no estoque
    $stmtEstoqueBaixo = $pdo->query("SELECT nome, quantidade FROM tbProdutos WHERE quantidade <= 10");
    $produtosBaixoEstoque = $stmtEstoqueBaixo->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="_css/dashboard.css">
</head>
<body>
    <header>
        <h1>Bem-vindo(a), <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
        <nav>
            <a href="php_action/logout.php">Sair</a> <!-- Link para logout -->
        </nav>
    </header>

    <div class="dashboard-container">
        <?php if ($tipoUsuario === 'funcionario'): ?>
            <!-- Resumo de vendas -->
            <section class="resumo-vendas">
                <h2>Resumo de Vendas</h2>
                <p>Total de vendas realizadas: <?php echo $resumoVendas['totalVendas']; ?></p>
                <p>Total de itens vendidos: <?php echo $resumoVendas['totalItensVendidos']; ?></p>
            </section>

            <!-- Produtos com baixa quantidade no estoque -->
            <section class="estoque-baixo">
                <h2>Produtos com baixa quantidade no estoque</h2>
                <?php if (count($produtosBaixoEstoque) > 0): ?>
                    <ul>
                        <?php foreach ($produtosBaixoEstoque as $produto): ?>
                            <li><?php echo htmlspecialchars($produto['nome']); ?>: <?php echo $produto['quantidade']; ?> unidades restantes</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Todos os produtos estão com estoque suficiente.</p>
                <?php endif; ?>
            </section>

            <!-- Links rápidos para gerenciamento -->
            <section class="gerenciamento">
                <h2>Gerenciamento</h2>
                <ul>
                    <li><a href="produtos.php">Gerenciar Produtos</a></li>
                    <li><a href="vendas.php">Ver Histórico de Vendas</a></li>
                </ul>
            </section>

            <!-- Opção de cadastrar funcionário -->
            <section class="cadastro-funcionario">
                <h2>Área Administrativa</h2>
                <a href="cadastro_funcionario.php">Cadastrar Novo Funcionário</a>
            </section>
        <?php else: ?>
            <!-- Caso seja cliente, exibe uma mensagem diferente -->
            <section class="area-cliente">
                <h2>Menu</h2>
                <p>Acesse nosso cardápio <a href="menu.php">clicando aqui</a> para realizar seu pedido.</p>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>
