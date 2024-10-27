<?php
include_once 'db.php'; // Inclui a conexão com o banco de dados

if (isset($_GET['codProd'])) {
    $codProd = intval($_GET['codProd']);
    $stmt = $pdo->prepare("SELECT * FROM tbProdutos WHERE codProd = :codProd");
    $stmt->bindParam(':codProd', $codProd, PDO::PARAM_INT);
    $stmt->execute();

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($produto) {
        echo json_encode($produto);
    } else {
        echo json_encode(['error' => 'Produto não encontrado']);
    }
} else {
    echo json_encode(['error' => 'Código do produto não fornecido']);
}
?>
