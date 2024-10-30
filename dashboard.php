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
$stmt = $pdo->prepare("SELECT nome, tipoUsuario FROM tbUsuarios WHERE email = :email");
$stmt->bindParam(':email', $emailUsuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário é admin
if ($usuario['tipoUsuario'] !== 'admin') {
    header('Location: /Tcc/menu.php'); // Redireciona para a página do menu se não for admin
    exit();
}

// Resumo de vendas
$totalVendasConfirmadas = 0;
$totalVendasCanceladas = 0;

// Resumo de vendas confirmadas
$stmtVendas = $pdo->query("SELECT COUNT(*) AS totalVendasConfirmadas FROM tbVendas WHERE status = 'confirmada'");
$totalVendasConfirmadas = $stmtVendas->fetchColumn();

// Resumo de vendas canceladas
$stmtCanceladas = $pdo->query("SELECT COUNT(*) AS totalVendasCanceladas FROM tbVendas WHERE status = 'cancelada'");
$totalVendasCanceladas = $stmtCanceladas->fetchColumn();

// Produtos com baixa quantidade no estoque
$stmtEstoqueBaixo = $pdo->query("SELECT nome, quantidade FROM tbProdutos WHERE quantidade <= 10");
$produtosBaixoEstoque = $stmtEstoqueBaixo->fetchAll(PDO::FETCH_ASSOC);
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
        <!-- Resumo de vendas -->
        <section class="resumo-vendas">
            <h2>Resumo de Vendas</h2>
            <p>Total de vendas realizadas: <?php echo $totalVendasConfirmadas ?? 0; ?></p>
            <p>Total de vendas canceladas: <?php echo $totalVendasCanceladas ?? 0; ?></p>
        </section>

        <!-- Produtos com baixa quantidade no estoque -->
        <section class="estoque-baixo">
            <h2>Produtos com Baixa Quantidade no Estoque</h2>
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
                <li><a href="vendas.php">Gerenciar Pedidos</a></li>
                <li><a href="estoque.php">Gerenciar Produtos</a></li> 
                <li><a href="HistVendas.php">Histórico de Vendas</a></li> 
            </ul>
        </section>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
