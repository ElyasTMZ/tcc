<?php 
session_start();
include_once 'php_action/db.php';
include_once 'php_action/cartfunc.php'; // Inclui as funções do carrinho

// Verifica se o usuário está logado
if (!isset($_SESSION['codUsu'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Variáveis para mensagens de sucesso e erro
$successMessage = '';
$errorMessage = '';

// Verifica se o usuário fez a ação de checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkout') {
    // Verifica se o carrinho não está vazio
    if (!empty($_SESSION['cart'])) {
        // Obtém o usuário logado
        $codUsu = $_SESSION['codUsu'];

        // Obtém a data e hora atuais
        $dataVenda = date('Y-m-d');
        $horaVenda = date('H:i:s');

        // Captura o método de pagamento da sessão
        $metodoPagamento = $_SESSION['metodoPagamento'] ?? 'Não especificado';

        // Determina o status do pagamento
        $statusPagamento = ($metodoPagamento === 'pix') ? 'Realizado' : 'Pendente';

        $lastOrderId = null; // Inicializa a variável para armazenar o ID do último pedido

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

        // Mensagem de sucesso
        $successMessage = "Compra finalizada com sucesso!";
    } else {
        $errorMessage = "O carrinho está vazio.";
    }
} else {
    $errorMessage = "Ação inválida.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="_CSS/finalizar.css"> 
</head>
<body>
    <div class="container">
        <h1>Compra Finalizada</h1>
        
        <?php if (!empty($successMessage)): ?>
            <p class="success"><?php echo $successMessage; ?></p>
            <?php if (isset($lastOrderId) && $lastOrderId !== null): ?>
                <p>O código do seu pedido é: <strong><?php echo htmlspecialchars($lastOrderId); ?></strong></p>
                <p>Método de Pagamento: <strong><?php echo htmlspecialchars($metodoPagamento); ?></strong></p>
                <p>Status do Pagamento: <strong><?php echo htmlspecialchars($statusPagamento); ?></strong></p>
            <?php endif; ?>
        <?php else: ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        
        <a href="menu.php">Voltar ao Cardápio</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
