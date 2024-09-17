<?php
session_start();
// include_once 'header.php';
include_once 'php_action/db.php';
include_once 'php_action/cartfunc.php'; // Inclui as funções do carrinho

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkout') {
    // Verificar se o carrinho não está vazio
    if (!empty($_SESSION['cart'])) {
        // Obtém o usuário logado, por exemplo, de uma variável de sessão
        $codUsu = $_SESSION['user_id']; // Assuma que você tenha uma sessão de usuário

        // Obtém a data e hora atuais
        $dataVenda = date('Y-m-d');
        $horaVenda = date('H:i:s');

        // Registra cada item no carrinho
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $quantidade = $item['quantity'];
            // Registra a venda
            $lastOrderId = registrarVenda($dataVenda, $horaVenda, $quantidade, $codUsu, $item_id);

            if ($lastOrderId === null) {
                echo "Houve um problema ao registrar seu pedido. Por favor, tente novamente.";
                exit;
            }
        }

        // Limpa o carrinho após a finalização da compra
        $_SESSION['cart'] = [];

        echo "Compra finalizada com sucesso!";
    } else {
        echo "O carrinho está vazio.";
    }
} else {
    echo "Ação inválida.";
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
