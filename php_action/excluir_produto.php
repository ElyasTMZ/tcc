<?php
include 'db.php'; // Inclui o arquivo de conexão com o banco de dados
function excluirProduto($codProd) {
    global $pdo; // Utiliza a conexão global com o banco de dados

    try {
        // SQL para deletar dados na tabela tbProdutos
        $sql = "DELETE FROM tbProdutos WHERE codProd = :codProd";
        $stmt = $pdo->prepare($sql); // Prepara a declaração SQL

        // Bind do parâmetro
        $stmt->bindParam(':codProd', $codProd, PDO::PARAM_INT);

        $stmt->execute(); // Executa a declaração SQL

        echo "Produto excluído com sucesso!";
    } catch (PDOException $e) {
        // Exibe erro se ocorrer
        echo "Erro: " . $e->getMessage();
    }
}
?>
