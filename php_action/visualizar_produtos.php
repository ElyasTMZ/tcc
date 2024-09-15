<?php
include 'db.php'; // Inclui o arquivo de conexão com o banco de dados
function visualizarProdutos() {
    global $pdo; // Utiliza a conexão global com o banco de dados

    try {
        // SQL para selecionar todos os produtos da tabela tbProdutos
        $sql = "SELECT * FROM tbProdutos";
        $stmt = $pdo->query($sql); // Executa a consulta SQL
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os resultados

        return $produtos; // Retorna a lista de produtos
    } catch (PDOException $e) {
        // Exibe erro se ocorrer
        echo "Erro: " . $e->getMessage();
    }
}
?>
