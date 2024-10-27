<?php 
session_start();
include_once 'php_action/db.php'; // Inclua sua conexão com o banco de dados
include_once 'php_action/FuncPagamento.php'; // Inclui o arquivo com a função finalizarCompra

// Verifica se o usuário está logado
if (!isset($_SESSION['codUsu'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Verifica se os dados do pagamento foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codUsu = $_SESSION['codUsu'];
    $codigoPagamento = $_POST['codigoPagamento'];
    $valorPagamento = $_POST['valorPagamento'];

    // Aqui você pode adicionar lógica para verificar se o pagamento foi realmente efetuado
    // Se o pagamento for confirmado, registre a venda
    $resultado = finalizarCompra($codUsu); // Chama a função para registrar a venda

    if ($resultado['sucesso']) {
        // Redireciona para a página de finalização com o código da venda
        header('Location: finalizar.php?codVenda=' . $resultado['ultimoIdPedido']); // Usa o ID da venda
        exit();
    } else {
        $erro = $resultado['mensagemErro'] ?: "Erro ao registrar a venda. Tente novamente.";
    }
} else {
    header('Location: pagamento.php'); // Redireciona se não houver dados
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pagamento</title>
</head>
<body>
    <div class="container">
        <h1>Confirmação de Pagamento</h1>
        <?php if (isset($erro)): ?>
            <p class="error"><?php echo htmlspecialchars($erro); ?></p>
        <?php else: ?>
            <p>Seu pagamento foi confirmado!</p>
            <p>Obrigado pela sua compra.</p>
        <?php endif; ?>
        <a href="menu.php">Voltar ao Cardápio</a>
    </div>
</body>
</html>
