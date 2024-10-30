<?php
include 'cart_functions.php';

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    removeFromCart($item_id);
}

// Redireciona de volta ao carrinho
header("Location: cart.php");
exit();
?>
