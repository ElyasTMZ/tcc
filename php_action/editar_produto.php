<?php
include 'db.php'; // Inclui o arquivo de conexão com o banco de dados

function editarProduto($codProd, $name, $description, $quantity, $price, $validade) {
    global $pdo;

    try {
        $sql = "UPDATE tbProdutos SET nome = ?, descricao = ?, quantidade = ?, valor = ?, validade = ? WHERE codProd = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $quantity, $price, $validade, $codProd]);
        return "Produto editado com sucesso.";
    } catch (Exception $e) {
        return "Erro ao editar produto: " . $e->getMessage();
    }
}

?>
