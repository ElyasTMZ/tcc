<?php
session_start();

include_once 'php_action/db.php';
include 'php_action/cartfunc.php'; 
include 'php_action/FuncPagamento.php';
include_once 'header.php'; // Inclui o cabeçalho

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Verifica se o carrinho existe na sessão e não está vazio
if (empty($_SESSION['cart'])) {
    header('Location: carrinho.php'); // Redireciona para o carrinho se estiver vazio
    exit();
}

// Itens do carrinho
$itens = $_SESSION['cart'];

// Calcula o total considerando a quantidade de cada item
$total = 0;
foreach ($itens as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura o método de pagamento selecionado
    $metodoPagamento = $_POST['payment'] ?? '';

    // Verifica se um método de pagamento foi selecionado
    if (!empty($metodoPagamento)) {
        // Define o ID do usuário a partir da sessão
        $codUsu = $_SESSION['codUsu'];

        // Processa o pagamento
        if ($metodoPagamento === 'dinheiro') {
            // Gera um código de pedido único
            $codigoPedido = uniqid('pedido_');

            // Salva o método de pagamento na sessão
            $_SESSION['metodoPagamento'] = $metodoPagamento;

            // Finaliza a compra e registra a venda
            $resultado = finalizarCompra($codUsu); // Usando a função que você já implementou

            // Verifica se a compra foi finalizada com sucesso
            if (!empty($resultado['mensagemSucesso'])) {
                // Redireciona para a página de finalização com o código da venda retornado
                header('Location: finalizar.php?codVenda=' . $resultado['ultimoIdPedido']); // Usa o ID da venda
                exit();
            } else {
                $erro = $resultado['mensagemErro'] ?: "Erro ao processar seu pedido. Tente novamente.";
            }
        } elseif ($metodoPagamento === 'pix') {
            // Salva o método de pagamento e valor total na sessão
            $_SESSION['metodoPagamento'] = $metodoPagamento;
            $_SESSION['valorTotal'] = $total; // Salva o valor total

            // Redireciona para a página de pagamento via PIX
            header('Location: pagpix.php'); // Página onde o pagamento será finalizado
            exit();
        }
    } else {
        $erro = "Selecione um método de pagamento.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo de Pagamento</title>
    <link rel="stylesheet" href="_css/pagamento.css">
</head>
<body>
    <div class="container">
        <h1>Resumo de Pagamento</h1>
        <?php if (isset($erro)): ?>
            <p class="error"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>
        <div class="order-summary">
            <h2>Itens</h2>
            <?php foreach ($itens as $item): ?>
                <p><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>): <span class="price">R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></span></p>
            <?php endforeach; ?>
            <hr>
            <p class="total">Total: <span class="price">R$ <?php echo number_format($total, 2, ',', '.'); ?></span></p>
        </div>
        
        <form method="post" action="">
            <div class="payment-methods">
                <h2>Formas de Pagamento</h2>
                <label>
                    <input type="radio" name="payment" value="pix" required> PIX
                </label><br>
                <label>
                    <input type="radio" name="payment" value="dinheiro" required> Dinheiro
                </label>
            </div>
            
            <button type="submit" class="finalize-btn">Finalizar</button>
        </form>
    </div>
</body>
</html>
