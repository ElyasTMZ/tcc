<?php
session_start();
include_once 'header.php';
include_once 'php_action/db.php';
include_once 'php_action/cartfunc.php'; // Inclui as funções do carrinho

// Ações para aumentar ou diminuir quantidade via GET
if (isset($_GET['action']) && isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    if ($_GET['action'] == 'increase') {
        increaseItemQuantity($item_id);
    } elseif ($_GET['action'] == 'decrease') {
        decreaseItemQuantity($item_id);
    }
    // Redireciona para evitar reenvio do formulário e atualizar a página do carrinho
    header('Location: carrinho.php');
    exit;
}

// Adiciona item ao carrinho se a ação for 'add'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];

    addToCart($item_id, $item_name, $item_price);
    
    // Redireciona para evitar reenvio do formulário e para a página do carrinho
    header('Location: carrinho.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" type="text/css" href="_css/carrinho.css"> <!-- Inclua seu CSS para o carrinho -->
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
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item_id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>R$<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$<?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                            <td>
                                <!-- Botão para aumentar a quantidade -->
                                <a href="carrinho.php?action=increase&item_id=<?php echo $item_id; ?>" class="btn">+</a>
                                <!-- Botão para diminuir a quantidade -->
                                <a href="carrinho.php?action=decrease&item_id=<?php echo $item_id; ?>" class="btn">-</a>
                                <!-- Botão para remover o item -->
                                <a href="remove_item.php?item_id=<?php echo $item_id; ?>" class="btn">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p>Total: R$<?php echo number_format(getCartTotal(), 2, ',', '.'); ?></p>

            <!-- Formulário para finalizar a compra -->
            <form action="finalizar.php" method="post">
                <input type="hidden" name="action" value="checkout">
                <button type="submit">Finalizar Compra</button>
            </form>

        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
        <?php endif; ?>

        <a href="menu.php">Voltar ao Cardápio</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>

