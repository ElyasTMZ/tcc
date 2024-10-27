<?php 
session_start();
include_once 'php_action/db.php'; // Inclua sua conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['codUsu'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Verifica se o valor total foi armazenado na sessão
if (!isset($_SESSION['valorTotal'])) {
    header('Location: carrinho.php'); // Redireciona se não houver valor total
    exit();
}

// Recupera o valor total da sessão
$valorPagamento = $_SESSION['valorTotal'];

// Gerar um código de pagamento fictício
$codigoPagamento = uniqid('PIX-'); // Exemplo de código de pagamento
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX</title>
    <link rel="stylesheet" href="_CSS/finalizar.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-qrcode/1.0/jquery.qrcode.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Pedido Concluído</h1>
        <p>Realize o pagamento com o código abaixo.</p>
        
        <div class="payment-info">
            <div id="qrcode"></div> <!-- Onde o QR Code será gerado -->
            <p><strong>Código de Pagamento:</strong> <?php echo htmlspecialchars($codigoPagamento); ?></p>
            <p><strong>Valor a Pagar:</strong> R$ <?php echo number_format($valorPagamento, 2, ',', '.'); ?></p>
        </div>

        <p>Após realizar o pagamento, clique no botão abaixo para confirmar.</p>
        <form method="post" action="confirmaPag.php">
            <input type="hidden" name="codigoPagamento" value="<?php echo htmlspecialchars($codigoPagamento); ?>">
            <input type="hidden" name="valorPagamento" value="<?php echo $valorPagamento; ?>">
            <button type="submit" class="finalize-btn">Confirmar Pagamento</button>
        </form>

        <a href="menu.php">Voltar ao Cardápio</a>
    </div>

    <script>
        $(document).ready(function() {
            const valorPagamento = '<?php echo number_format($valorPagamento, 2, ',', '.'); ?>'; // Valor total
            const paymentCode = '<?php echo htmlspecialchars($codigoPagamento); ?>'; // Código de pagamento

            // Gera o QR Code
            $("#qrcode").qrcode(`Pagamento de R$ ${valorPagamento}\nCódigo: ${paymentCode}`); // Gera o QR Code com o texto
        });
    </script>
</body>
</html>
