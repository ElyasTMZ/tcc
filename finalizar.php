<?php
session_start();
include_once 'php_action/db.php';
include 'php_action/inserir.php'; // Inclua o arquivo onde a função registrarVenda está definida
include 'header.php';


// Verifique se a ação é a finalização da compra
if ($_POST['action'] === 'checkout') {
    // Aqui você deve ter um método para obter o código do usuário
    $codUsu = 1; // Suponha que o código do usuário seja 1, substitua com o código real
    $date = date('Y-m-d');
    $time = date('H:i:s');

    // Variável para armazenar o ID do último pedido
    $lastOrderId = null;

    foreach ($_SESSION['cart'] as $item_id => $item) {
        $quantity = $item['quantity'];
        $lastOrderId = registrarVenda($date, $time, $quantity, $codUsu, $item_id);
    }

    // Limpa o carrinho
    unset($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="_CSS/finalizar.css"> 
</head>
<body>
    <div class="container">
        <h1>Compra Finalizada</h1>
        <p>Obrigado pela sua compra!</p>
        <?php if ($lastOrderId !== null): ?>
            <p>O código do seu pedido é: <strong><?php echo $lastOrderId; ?></strong></p>
        <?php else: ?>
            <p>Houve um problema ao registrar seu pedido. Por favor, tente novamente.</p>
        <?php endif; ?>
        <a href="index.php">Voltar ao Cardápio</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
