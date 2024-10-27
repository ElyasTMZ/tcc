<?php
session_start();
include_once 'header.php';
include_once 'php_action/db.php';
include_once 'php_action/cartfunc.php'; // Inclui as funções do carrinho

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Verifica se o código da venda foi passado pela URL
if (!isset($_GET['codVenda'])) {
    header('Location: carrinho.php'); // Redireciona de volta ao carrinho se não houver código
    exit();
}

// Obtém o código da venda da URL
$codVenda = htmlspecialchars($_GET['codVenda']);

// Limpa o carrinho após a compra
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Agradecimento pela Compra</title>
    <link rel="stylesheet" type="text/css" href="_css/finalizar.css">
</head>
<body>
    <div class="container">
        <h1>Obrigado pela sua Compra!</h1>
        <p>Seu pedido foi processado com sucesso.</p>
        <p>Apresente o código abaixo na cantina para retirar seu pedido:</p>
        <h2>Código do Pedido: <strong><?php echo $codVenda; ?></strong></h2>

        <p><a href="menu.php" class="btn">Voltar ao Cardápio</a></p>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
