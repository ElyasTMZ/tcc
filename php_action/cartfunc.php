<?php


// Função para adicionar um item ao carrinho
function addToCart($item_id, $item_name, $item_price) {
    // Inicializa o carrinho se não existir
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Adiciona ou aumenta a quantidade do item no carrinho
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        $_SESSION['cart'][$item_id] = array(
            'name' => htmlspecialchars($item_name), // Protege contra XSS
            'price' => floatval($item_price), // Garante que o preço é um número
            'quantity' => 1
        );
    }
}

// Função para remover um item do carrinho
function removeFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Função para calcular o total do carrinho
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// Aumentar a quantidade de um item no carrinho
function increaseItemQuantity($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    }
}

// Diminuir a quantidade de um item no carrinho
function decreaseItemQuantity($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        if ($_SESSION['cart'][$item_id]['quantity'] > 1) {
            $_SESSION['cart'][$item_id]['quantity']--;
        } else {
            unset($_SESSION['cart'][$item_id]);  // Remove o item se a quantidade for 1
        }
    }
}
// Função para registrar uma venda
function registrarVenda($dataVenda, $horaVenda, $quantidade, $codUsu, $codProd, $status) {
    global $pdo; // Assumindo que você tem uma conexão PDO configurada

    // Prepare a consulta SQL para inserir uma nova venda
    $sql = "INSERT INTO tbVendas (dataVenda, horaVenda, quantidade, codUsu, codProd, status) 
            VALUES (:dataVenda, :horaVenda, :quantidade, :codUsu, :codProd, :status)";
    $stmt = $pdo->prepare($sql);

    // Executa a consulta
    $stmt->execute([
        ':dataVenda' => $dataVenda,
        ':horaVenda' => $horaVenda,
        ':quantidade' => $quantidade,
        ':codUsu' => $codUsu,
        ':codProd' => $codProd,
        ':status' => $status // Define o status da venda
    ]);

    // Retorna o ID do último pedido inserido
    return $pdo->lastInsertId();
}


?>
