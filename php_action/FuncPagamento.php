<?php
include_once 'php_action/db.php';
include_once 'php_action/cartfunc.php';

function finalizarCompra($codUsu) {
    $mensagemSucesso = '';
    $mensagemErro = '';

    if (empty($_SESSION['cart'])) {
        return [
            'sucesso' => false,
            'mensagemErro' => "O carrinho está vazio.",
            'ultimoIdPedido' => null,
            'metodoPagamento' => null,
            'statusPagamento' => null
        ];
    }

    $dataVenda = date('Y-m-d');
    $horaVenda = date('H:i:s');
    $metodoPagamento = $_SESSION['metodoPagamento'] ?? 'Não especificado';
    
    // Definir o status como 'pendente' para todas as vendas
    $statusPagamento = 'pendente';

    $ultimoIdPedido = null;

    foreach ($_SESSION['cart'] as $codProd => $item) {
        if (isset($item['quantity']) && !empty($codProd)) {
            $quantidade = $item['quantity'];
            $ultimoIdPedido = registrarVenda($dataVenda, $horaVenda, $quantidade, $codUsu, $codProd, $metodoPagamento, $statusPagamento);

            if ($ultimoIdPedido === null) {
                $mensagemErro = "Houve um problema ao registrar seu pedido. Por favor, tente novamente.";
                break;
            }
        } else {
            $mensagemErro = "Item do carrinho inválido.";
            break;
        }
    }

    if ($mensagemErro === '') {
        $_SESSION['cart'] = []; // Limpa o carrinho
        unset($_SESSION['metodoPagamento'], $_SESSION['codigoPedido'], $_SESSION['statusPagamento']);
        $mensagemSucesso = "Compra finalizada com sucesso!";
    }

    return [
        'sucesso' => empty($mensagemErro),
        'mensagemSucesso' => $mensagemSucesso,
        'mensagemErro' => $mensagemErro,
        'ultimoIdPedido' => $ultimoIdPedido,
        'metodoPagamento' => $metodoPagamento,
        'statusPagamento' => $statusPagamento
    ];
}

function registrarVenda($dataVenda, $horaVenda, $quantidade, $codUsu, $codProd, $metodoPagamento, $status) {
    global $pdo;
    try {
        // Insere a venda com o status 'pendente'
        $stmt = $pdo->prepare("INSERT INTO tbVendas (dataVenda, horaVenda, quantidade, codUsu, codProd, metodoPagamento, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$dataVenda, $horaVenda, $quantidade, $codUsu, $codProd, $metodoPagamento, $status]);

        return $pdo->lastInsertId(); // Retorna o ID da venda registrada
    } catch (Exception $e) {
        echo 'Erro: ' . $e->getMessage();
        return null;
    }
}

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
?>
