<?php
include 'db.php'; // Inclui o arquivo de conexão com o banco de dados

function inserirProduto($descricao, $quantidade, $valor, $validade, $dataEntrada, $horaEntrada) {
    global $pdo; // Utiliza a conexão global com o banco de dados

    try {
        // Código SQL para inserir os dados na tabela tbProdutos
        $sql = "INSERT INTO tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada) 
                VALUES (:descricao, :quantidade, :valor, :validade, :dataEntrada, :horaEntrada)";

        // Preparar a declaração
        $stmt = $pdo->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':validade', $validade);
        $stmt->bindParam(':dataEntrada', $dataEntrada);
        $stmt->bindParam(':horaEntrada', $horaEntrada);

        // Executar a declaração
        $stmt->execute();
        echo "Produto adicionado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST); // Adicione esta linha para verificar os dados recebidos

    // Verifica se todos os campos necessários foram preenchidos
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
        $descricao = $_POST['description'];
        $valor = $_POST['price'];
        $quantidade = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $validade = $_POST['validade'];

        // Obtém a data e hora atuais
        $dataEntrada = date('Y-m-d');
        $horaEntrada = date('H:i:s');

        // Chama a função para inserir o produto
        inserirProduto($descricao, $quantidade, $valor, $validade, $dataEntrada, $horaEntrada);
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

function registrarVenda($dataVenda, $horaVenda, $quantidade, $codUsu, $codProd) {
    global $pdo;

    try {
        $stmt = $pdo->prepare('INSERT INTO tbVendas (dataVenda, horaVenda, quantidade, codUsu, codProd) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$dataVenda, $horaVenda, $quantidade, $codUsu, $codProd]);
        return $pdo->lastInsertId(); // Retorna o ID do último pedido inserido
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return null;
    }
}
?>
