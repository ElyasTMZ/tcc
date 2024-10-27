<?php
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados

// Busca todas as vendas, incluindo canceladas e confirmadas
$stmt = $pdo->query("SELECT * FROM tbVendas");
$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Histórico de Vendas</title>
    <link rel="stylesheet" href="_css/vendas.css">
</head>
<body>
    <div class="content">
        <h1>Histórico de Vendas</h1>

        <table>
            <thead>
                <tr>
                    <th>ID Venda</th>
                    <th>Nome do Cliente</th>
                    <th>Data Venda</th>
                    <th>Hora Venda</th>
                    <th>Quantidade</th>
                    <th>Produto</th>
                    <th>Valor Total</th>
                    <th>Método de Pagamento</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($vendas) > 0): ?>
                    <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($venda['codVenda']); ?></td>
                            <td>
                                <?php
                                // Busca o nome do cliente
                                $stmtCliente = $pdo->prepare("SELECT nome FROM tbUsuarios WHERE codUsu = :codUsu");
                                $stmtCliente->bindParam(':codUsu', $venda['codUsu']);
                                $stmtCliente->execute();
                                $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);
                                echo htmlspecialchars($cliente['nome'] ?? 'Cliente não encontrado');
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($venda['dataVenda']); ?></td>
                            <td><?php echo htmlspecialchars($venda['horaVenda']); ?></td>
                            <td><?php echo htmlspecialchars($venda['quantidade']); ?></td>
                            <td>
                                <?php
                                // Busca o nome do produto
                                $stmtProd = $pdo->prepare("SELECT nome FROM tbProdutos WHERE codProd = :codProd");
                                $stmtProd->bindParam(':codProd', $venda['codProd']);
                                $stmtProd->execute();
                                $produto = $stmtProd->fetch(PDO::FETCH_ASSOC);
                                echo htmlspecialchars($produto['nome'] ?? 'Produto não encontrado');
                                ?>
                            </td>
                            <td>
                                <?php
                                // Calcular o valor total
                                $valorTotal = $venda['quantidade'] * (
                                    $pdo->query("SELECT valor FROM tbProdutos WHERE codProd = {$venda['codProd']}")->fetchColumn()
                                );
                                echo "R$ " . number_format($valorTotal, 2, ',', '.');
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($venda['metodoPagamento']); ?></td>
                            <td><?php echo htmlspecialchars($venda['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Nenhuma venda encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Botão Voltar abaixo da tabela -->
        <a href="dashboard.php" class="back-button">Voltar</a> <!-- Botão Voltar -->
    </div>
</body>
</html>
