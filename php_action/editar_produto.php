<?php
include 'db.php'; // Inclui o arquivo de conexão com o banco de dados
function editarProduto($codProd, $descricao, $quantidade, $valor, $validade, $dataEntrada, $horaEntrada) {
    global $pdo; // Utiliza a conexão global com o banco de dados

    try {
        // SQL para atualizar dados na tabela tbProdutos
        $sql = "UPDATE tbProdutos SET descricao = :descricao, quantidade = :quantidade, valor = :valor, 
                validade = :validade, dataEntrada = :dataEntrada, horaEntrada = :horaEntrada
                WHERE codProd = :codProd";
        $stmt = $pdo->prepare($sql); // Prepara a declaração SQL

        // Bind dos parâmetros
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':validade', $validade);
        $stmt->bindParam(':dataEntrada', $dataEntrada);
        $stmt->bindParam(':horaEntrada', $horaEntrada);
        $stmt->bindParam(':codProd', $codProd, PDO::PARAM_INT);

        $stmt->execute(); // Executa a declaração SQL

        echo "Produto atualizado com sucesso!";
    } catch (PDOException $e) {
        // Exibe erro se ocorrer
        echo "Erro: " . $e->getMessage();
    }
}
?>
