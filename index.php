<?php
include 'db.php';
include 'header.php';

$query = 'SELECT * FROM tbProdutos';
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Cardápio</title>
    <link rel="stylesheet" href="_CSS/index.css"> <!-- Inclua seu CSS principal aqui -->
</head>
<body>
    <div class="content"> <!-- Adicione uma classe para estilizar o conteúdo -->
        <h1>Cardápio</h1>
        <ul>
            <?php foreach ($products as $product): ?>
                <li>
                    <?php echo htmlspecialchars($product['descricao']); ?> - R$<?php echo number_format($product['valor'], 2, ',', '.'); ?>
                    <form action="carrinho.php" method="post">
                        <input type="hidden" name="item_id" value="<?php echo $product['codProd']; ?>">
                        <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($product['descricao']); ?>">
                        <input type="hidden" name="item_price" value="<?php echo $product['valor']; ?>">
                        <button type="submit">Adicionar ao Carrinho</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="carrinho.php">Ver Carrinho</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>