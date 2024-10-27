<?php
session_start();
include_once 'header.php';
include_once 'php_action/db.php';
include_once 'php_action/cartfunc.php'; // Inclui as funções do carrinho

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: /Tcc/login.php');
    exit();
}

// Função para obter a quantidade disponível em estoque
function getProductStock($item_id, $pdo) {
    $stmt = $pdo->prepare("SELECT quantidade FROM tbProdutos WHERE codProd = :codProd");
    $stmt->bindParam(':codProd', $item_id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Ações para aumentar ou diminuir quantidade via GET
if (isset($_GET['action']) && isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $stock = getProductStock($item_id, $pdo);

    if ($_GET['action'] == 'increase') {
        if (isset($_SESSION['cart'][$item_id]) && $_SESSION['cart'][$item_id]['quantity'] < $stock) {
            increaseItemQuantity($item_id);
        }
    } elseif ($_GET['action'] == 'decrease') {
        decreaseItemQuantity($item_id);
    }
    header('Location: carrinho.php');
    exit;
}

// Ação para remover item do carrinho
if (isset($_GET['remove_item_id'])) {
    $item_id = $_GET['remove_item_id'];
    removeFromCart($item_id);
    header('Location: carrinho.php');
    exit;
}

// Adiciona item ao carrinho se a ação for 'add'
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    addToCart($item_id, $item_name, $item_price);
    header('Location: carrinho.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" type="text/css" href="_css/carrinho.css"> <!-- CSS do carrinho -->
</head>
<body>
    <div class="container">
        <h1>Carrinho de Compras</h1>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
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
                        <tr class="cart-item">
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="carrinho.php?action=increase&item_id=<?php echo $item_id; ?>" class="btn" <?php echo ($item['quantity'] >= getProductStock($item_id, $pdo)) ? 'style="display:none;"' : ''; ?>>+</a>
                                <a href="carrinho.php?action=decrease&item_id=<?php echo $item_id; ?>" class="btn">-</a>
                                <a href="carrinho.php?remove_item_id=<?php echo $item_id; ?>" class="btn">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="cart-total">Total: R$ <?php echo number_format(getCartTotal(), 2, ',', '.'); ?></p>

            <!-- Formulário para finalizar a compra -->
            <form action="pagamento.php" method="post"> <!-- Link para a página de pagamento -->
                <input type="hidden" name="action" value="checkout">
                <button type="submit" class="btn checkout-button">Finalizar Compra</button>
            </form>

        <?php else: ?>
            <p class="empty-cart-message">Seu carrinho está vazio.</p>
        <?php endif; ?>

        <a href="menu.php" class="btn return-button">Voltar ao Cardápio</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
