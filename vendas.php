<?php
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados
include_once 'php_action/FuncVendas.php'; // Inclui as funções de vendas

// Busca todas as vendas com status 'pendente' ou 'Realizado'
$stmt = $pdo->query("SELECT * FROM tbVendas WHERE status IN ('pendente', 'Realizado')");
$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirmar'])) {
        $codVenda = $_POST['codVenda'];
        confirmarVenda($codVenda);
        echo "<script>alert('Venda confirmada com sucesso!'); window.location.href='vendas.php';</script>";
    } elseif (isset($_POST['cancelar'])) {
        $codVenda = $_POST['codVenda'];
        cancelarVenda($codVenda);
        echo "<script>alert('Venda cancelada com sucesso!'); window.location.href='vendas.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Validar Vendas</title>
    <link rel="stylesheet" href="_css/vendas.css">
</head>
<body>
    <div class="content">
        <h1>Confirmação de Vendas</h1>

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
                    <th>Ações</th>
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
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="codVenda" value="<?php echo $venda['codVenda']; ?>">
                                    <?php if ($venda['status'] === 'pendente' || $venda['status'] === 'Realizado'): ?>
                                        <button type="submit" name="confirmar">Confirmar Venda</button>
                                    <?php endif; ?>
                                    <button type="submit" name="cancelar">Cancelar Venda</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">Nenhuma venda encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Botões Voltar e Histórico abaixo da tabela -->
        <div class="button-group">
            <a href="dashboard.php" class="back-button">Voltar</a> <!-- Botão Voltar -->
            <a href="HistVendas.php" class="history-button">Ver Histórico</a> <!-- Botão Ver Histórico -->
        </div>
    </div>
</body>
</html>
