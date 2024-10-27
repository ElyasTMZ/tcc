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

// Consulta para obter os 5 pedidos pendentes
$queryPendente = "SELECT v.codVenda, v.dataVenda, v.horaVenda, v.quantidade, v.metodoPagamento, v.status, p.nome AS produtoNome 
                  FROM tbVendas v 
                  JOIN tbProdutos p ON v.codProd = p.codProd 
                  WHERE v.codUsu = :codUsu AND v.status = 'pendente' 
                  ORDER BY v.dataVenda DESC LIMIT 5"; 

$stmtPendente = $pdo->prepare($queryPendente);
$stmtPendente->execute(['codUsu' => $codUsu]);
$pedidosPendentes = $stmtPendente->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obter os 5 pedidos cancelados ou confirmados
$queryConcluido = "SELECT v.codVenda, v.dataVenda, v.horaVenda, v.quantidade, v.metodoPagamento, v.status, p.nome AS produtoNome 
                   FROM tbVendas v 
                   JOIN tbProdutos p ON v.codProd = p.codProd 
                   WHERE v.codUsu = :codUsu AND (v.status = 'cancelada' OR v.status = 'confirmada') 
                   ORDER BY v.dataVenda DESC LIMIT 5"; 

$stmtConcluido = $pdo->prepare($queryConcluido);
$stmtConcluido->execute(['codUsu' => $codUsu]);
$pedidosConcluidos = $stmtConcluido->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="_css/meuped.css">
    <style>
        .pedido-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .pedido-list {
            width: 48%; /* Cada lista ocupa cerca de 48% da largura */
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #c10a52;
            font-size: 22px;
            margin-bottom: 10px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>PEDIDOS</h1>

        <div class="pedido-container">
            <div class="pedido-list">
                <h2>Pedidos Pendentes</h2>
                <?php if ($pedidosPendentes): ?>
                    <ul>
                        <?php foreach ($pedidosPendentes as $pedido): ?>
                            <li>
                                Código do Pedido: <strong><?php echo htmlspecialchars($pedido['codVenda']); ?></strong><br>
                                Produto: <strong><?php echo htmlspecialchars($pedido['produtoNome']); ?></strong><br>
                                Quantidade: <strong><?php echo htmlspecialchars($pedido['quantidade']); ?></strong><br>
                                Data do Pedido: <strong><?php echo htmlspecialchars($pedido['dataVenda']); ?></strong><br>
                                Hora do Pedido: <strong><?php echo htmlspecialchars($pedido['horaVenda']); ?></strong><br>
                                Método de Pagamento: <strong><?php echo htmlspecialchars($pedido['metodoPagamento']); ?></strong><br>
                                Status: <strong><?php echo htmlspecialchars($pedido['status']); ?></strong><br>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Você não tem pedidos pendentes.</p>
                <?php endif; ?>
            </div>

            <div class="pedido-list">
                <h2>Pedidos Cancelados/Confirmados</h2>
                <?php if ($pedidosConcluidos): ?>
                    <ul>
                        <?php foreach ($pedidosConcluidos as $pedido): ?>
                            <li>
                                Código do Pedido: <strong><?php echo htmlspecialchars($pedido['codVenda']); ?></strong><br>
                                Produto: <strong><?php echo htmlspecialchars($pedido['produtoNome']); ?></strong><br>
                                Quantidade: <strong><?php echo htmlspecialchars($pedido['quantidade']); ?></strong><br>
                                Data do Pedido: <strong><?php echo htmlspecialchars($pedido['dataVenda']); ?></strong><br>
                                Hora do Pedido: <strong><?php echo htmlspecialchars($pedido['horaVenda']); ?></strong><br>
                                Método de Pagamento: <strong><?php echo htmlspecialchars($pedido['metodoPagamento']); ?></strong><br>
                                Status: <strong><?php echo htmlspecialchars($pedido['status']); ?></strong><br>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Você não tem pedidos cancelados ou confirmados.</p>
                <?php endif; ?>
            </div>
        </div>

        <button onclick="window.location.href='menu.php'">Voltar ao Início</button>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
