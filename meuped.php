<?php
session_start();
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados
include 'header.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['codUsu'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Obtém o código do usuário logado
$codUsu = $_SESSION['codUsu'];

// Consulta para obter os pedidos do usuário logado
$query = "SELECT codVenda FROM tbVendas WHERE codUsu = :codUsu ORDER BY dataVenda DESC"; // Ordena por data da venda
$stmt = $pdo->prepare($query);
$stmt->execute(['codUsu' => $codUsu]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="_css/meuped.css">
</head>
<body>

    <div class="container">
        <h1>PEDIDOS CONCLUÍDOS!</h1>

        <?php if ($pedidos): ?>
            <p>Abaixo estão os códigos dos seus pedidos:</p>
            <ul>
                <?php foreach ($pedidos as $pedido): ?>
                    <li>Código do Pedido: <strong><?php echo htmlspecialchars($pedido['codVenda']); ?></strong></li>
                <?php endforeach; ?>
            </ul>
            <p class="instructions">Apresente o código do pedido na cantina para retirada.</p>
        <?php else: ?>
            <p>Você ainda não fez nenhum pedido. Faça um pedido para que ele apareça aqui!</p>
        <?php endif; ?>

        <button onclick="window.location.href='menu.php'">Voltar ao Início</button>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>