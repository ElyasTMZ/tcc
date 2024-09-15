<?php
include 'php_action/db.php'; // Inclua a conexão com o banco de dados
include 'header.php';
// Consulta para buscar todos os produtos
$query = 'SELECT * FROM tbProdutos';
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Cardápio</title>
    <link rel="stylesheet" href="_css/index.css">
</head>
<body>
    <div class="cardapio-container">
        <div class="cardapio-content">
            <?php foreach ($products as $product): ?>
                <div class="cardapio-item">
                    <h3><?php echo htmlspecialchars($product['descricao']); ?></h3>
                    <p>Descrição: <?php echo htmlspecialchars($product['descricao']); ?></p>
                    <p>R$ <?php echo number_format($product['valor'], 2, ',', '.'); ?></p>
                    <form action="carrinho.php" method="post">
                        <input type="hidden" name="item_id" value="<?php echo $product['codProd']; ?>">
                        <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($product['descricao']); ?>">
                        <input type="hidden" name="item_price" value="<?php echo $product['valor']; ?>">
                        <button type="submit">Adicionar ao Carrinho</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>