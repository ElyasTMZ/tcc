<?php
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados

// Função para confirmar venda e atualizar estoque
function confirmarVenda($codVenda) {
    global $pdo;
    try {
        // Obtém os detalhes da venda antes de confirmar
        $stmtVenda = $pdo->prepare("SELECT codProd, quantidade FROM tbVendas WHERE codVenda = :codVenda");
        $stmtVenda->bindParam(':codVenda', $codVenda);
        $stmtVenda->execute();
        $venda = $stmtVenda->fetch(PDO::FETCH_ASSOC);

        // Verifica se a venda existe
        if ($venda) {
            // Atualiza o status da venda para 'confirmada'
            $stmt = $pdo->prepare("UPDATE tbVendas SET status = 'confirmada' WHERE codVenda = :codVenda");
            $stmt->bindParam(':codVenda', $codVenda);
            $stmt->execute();

            // Atualiza o estoque
            $stmtEstoque = $pdo->prepare("UPDATE tbProdutos SET quantidade = quantidade - :quantidade WHERE codProd = :codProd");
            $stmtEstoque->bindParam(':quantidade', $venda['quantidade']);
            $stmtEstoque->bindParam(':codProd', $venda['codProd']);
            $stmtEstoque->execute();
        } else {
            throw new Exception("Venda não encontrada.");
        }
    } catch (Exception $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}

function cancelarVenda($codVenda) {
    global $pdo;
    try {
        // Atualiza o status da venda para 'cancelada'
        $stmt = $pdo->prepare("UPDATE tbVendas SET status = 'cancelada' WHERE codVenda = :codVenda");
        $stmt->bindParam(':codVenda', $codVenda);
        $stmt->execute();
    } catch (Exception $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}

function calcularTotalVendasPendentes($pdo) {
    $query = "
        SELECT v.codUsu, SUM(v.quantidade * p.valor) AS total_vendas
        FROM tbVendas v
        JOIN tbProdutos p ON v.codProd = p.codProd
        WHERE v.status = 'pendente'
        GROUP BY v.codUsu
    ";
    
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
