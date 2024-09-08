<?php
session_start();
include 'db.php';
include 'header.php';

// Função para adicionar um item ao carrinho
function addToCart($item_id, $item_name, $item_price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        $_SESSION['cart'][$item_id] = array(
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => 1
        );
    }
}

// Adiciona item ao carrinho se os dados forem enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    
    addToCart($item_id, $item_name, $item_price);
}

// Função para calcular o total do carrinho
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" type="text/css" href="_css/carrinho.css">
</head>
<body>
    <div class="container">
        <h1>Carrinho de Compras</h1>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item_id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>R$<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$<?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>Total: R$<?php echo number_format(getCartTotal(), 2, ',', '.'); ?></p>

            <form action="finalizar.php" method="post">
                <input type="hidden" name="action" value="checkout">
                <button type="submit">Finalizar Compra</button>
            </form>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>

        <a href="index.php">Voltar ao Cardápio</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
