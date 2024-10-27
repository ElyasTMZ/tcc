<?php
include 'db.php'; // Inclui o arquivo de conexão com o banco de dados

function inserirProduto($nome, $descricao, $quantidade, $valor, $validade, $dataEntrada, $horaEntrada) {
    global $pdo;

    try {
        // SQL para inserir o produto
        $sql = "INSERT INTO tbProdutos (nome, descricao, quantidade, valor, validade, dataEntrada, horaEntrada) VALUES (:nome, :descricao, :quantidade, :valor, :validade, :dataEntrada, :horaEntrada)";
        $stmt = $pdo->prepare($sql);
        
        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':validade', $validade);
        $stmt->bindParam(':dataEntrada', $dataEntrada);
        $stmt->bindParam(':horaEntrada', $horaEntrada);
        
        $stmt->execute(); // Executa a declaração SQL
        
        return $pdo->lastInsertId(); // Retorna o ID do produto inserido
    } catch (PDOException $e) {
        error_log("Erro: " . $e->getMessage()); // Log do erro para depuração
        return false; // Retorna false em caso de erro
    }
}

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $nome = $_POST['name'];
    $descricao = $_POST['description'];
    $quantidade = intval($_POST['quantity']);
    $valor = $_POST['price'];
    $validade = $_POST['validade'];
    $dataEntrada = date('Y-m-d');
    $horaEntrada = date('H:i:s');

    $id = inserirProduto($nome, $descricao, $quantidade, $valor, $validade, $dataEntrada, $horaEntrada);
    
    if ($id) {
        echo json_encode(["message" => "Produto adicionado com sucesso!", "id" => $id]);
    } else {
        echo json_encode(["error" => "Erro ao adicionar produto."]);
    }
}

function registrarVenda($dataVenda, $horaVenda, $quantidade, $codUsu, $codProd) {
    global $pdo;

    try {
        // Preparar a declaração SQL
        $stmt = $pdo->prepare('INSERT INTO tbVendas (dataVenda, horaVenda, quantidade, codUsu, codProd) VALUES (?, ?, ?, ?, ?)');
        // Executar a declaração com os parâmetros fornecidos
        $stmt->execute([$dataVenda, $horaVenda, $quantidade, $codUsu, $codProd]);
        // Retorna o ID do último pedido inserido
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        // Exibir mensagem de erro
        echo "Erro ao registrar a venda: " . $e->getMessage();
        // Retorna null em caso de erro
        return null;
    }
}
?>
