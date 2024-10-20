<?php
session_start();

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

// Exemplo de itens do carrinho (em uma aplicação real, você buscaria do banco de dados)
$itens = $_SESSION['cart']; // Assume que o carrinho está salvo na sessão

// Calcula o total
$total = array_sum(array_column($itens, 'price'));

// Variáveis para armazenar informações do pagamento
$metodoPagamento = '';
$codigoPedido = uniqid('pedido_');
$statusPagamento = 'Pendente';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Depuração: Mostra os dados enviados
    var_dump($_POST); // Remova isso em produção, é só para debug

    // Captura o método de pagamento selecionado
    $metodoPagamento = $_POST['payment'] ?? ''; // Captura o valor ou um vazio

    // Define o status de pagamento com base no método escolhido
    $statusPagamento = ($metodoPagamento === 'pix') ? 'Realizado' : 'Pendente';

    // Armazena as informações na sessão para uso posterior
    $_SESSION['codigoPedido'] = $codigoPedido;
    $_SESSION['metodoPagamento'] = $metodoPagamento;
    $_SESSION['statusPagamento'] = $statusPagamento;

    // Aqui você pode adicionar qualquer lógica adicional necessária, como salvar o pedido no banco de dados.
} else {
    echo "Não foi uma requisição POST.";
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
        <div class="order-summary">
            <h2>Itens</h2>
            <?php foreach ($itens as $item): ?>
                <p><?php echo htmlspecialchars($item['name']); ?>: <span class="price">R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></span></p>
            <?php endforeach; ?>
            <hr>
            <p class="total">Total: <span class="price">R$ <?php echo number_format($total, 2, ',', '.'); ?></span></p>
        </div>
        
        <form method="post" action="pagamento.php">
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

        <?php if (!empty($metodoPagamento)): ?>
            <div class="payment-confirmation">
                <h2>Confirmação do Pagamento</h2>
                <p>Método de Pagamento: <strong><?php echo htmlspecialchars($metodoPagamento); ?></strong></p>
                <p>Código do Pedido: <strong><?php echo htmlspecialchars($codigoPedido); ?></strong></p>
                <p>Status do Pagamento: <strong><?php echo htmlspecialchars($statusPagamento); ?></strong></p>
                <hr>
                <p><a href="finalizar.php">Confirmar e Finalizar</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>