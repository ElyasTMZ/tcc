<?php
include_once 'db.php'; // Inclui a conexão com o banco de dados

function excluirProduto($codProd) {
    global $pdo;

    try {
        // SQL para excluir o produto
        $sql = "DELETE FROM tbProdutos WHERE codProd = :codProd";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codProd', $codProd, PDO::PARAM_INT);
        $stmt->execute(); // Executa a declaração SQL

        return $stmt->rowCount() > 0; // Retorna true se pelo menos uma linha foi afetada
    } catch (PDOException $e) {
        error_log("Erro: " . $e->getMessage()); // Log do erro para depuração
        return false; // Retorna false em caso de erro
    }
}

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $codProd = intval($_POST['codProd']); // Garantindo que seja um inteiro
    if (excluirProduto($codProd)) {
        echo json_encode(["message" => "Produto excluído com sucesso!"]);
    } else {
        echo json_encode(["error" => "Erro ao excluir produto. O produto pode não existir."]);
    }
}
?>
