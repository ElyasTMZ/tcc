<?php

// Função para adicionar um item ao carrinho
function addToCart($item_id, $item_name, $item_price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    } else {
        $_SESSION['cart'][$item_id] = [
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => 1
        ];
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
