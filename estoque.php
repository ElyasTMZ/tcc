<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos necessários foram preenchidos
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['fornecedor'])) {
        $nome = $_POST['name'];
        $descricao = $_POST['description'];
        $preco = $_POST['price'];
        $quantidade = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $codForn = intval($_POST['fornecedor']);
        $validade = $_POST['validade'];

        // Obtém a data e hora atuais
        $dataEntrada = date('Y-m-d');
        $horaEntrada = date('H:i:s');

        try {
            // Código SQL para inserir os dados na tabela tbProdutos
            $sql = "INSERT INTO tbProdutos (descricao, quantidade, valor, validade, dataEntrada, horaEntrada, codForn) 
                    VALUES (:descricao, :quantidade, :valor, :validade, :dataEntrada, :horaEntrada, :codForn)";

            // Preparar a declaração
            $stmt = $pdo->prepare($sql);

            // Bind dos parâmetros
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $stmt->bindParam(':valor', $preco);
            $stmt->bindParam(':validade', $validade);
            $stmt->bindParam(':dataEntrada', $dataEntrada);
            $stmt->bindParam(':horaEntrada', $horaEntrada);
            $stmt->bindParam(':codForn', $codForn, PDO::PARAM_INT);

            // Executar a declaração
            $stmt->execute();
            echo "Produto adicionado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolhas Cantina</title>
    <link rel="stylesheet" href="_CSS/estoque.css">
</head>
<body class="estoque-page">

    <header>
        <div class="logo">
            <img id="logo" alt="freeCodeCamp" src="logo etecia.png">
        </div>
        <div class="menu">
            <nav class="links">
                <ul>
                    <li><a href="#Link-Cardapio">Cardápio</a></li>
                    <li><a href="#Link-Pedidos">Pedidos</a></li>
                    <li><a href="#Link-Carrinho">Carrinho</a></li>
                    <li><a href="#Link-MeuPerfil">Meu Perfil</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="content">
        <h1>Escolhas Cantina</h1>

        <button class="add-button" onclick="showPopup()">Adicionar novo Produto</button>

        <div id="menu" class="menu">
            <!-- Itens do menu serão adicionados aqui -->
        </div>

        <!-- Popup para adicionar item -->
        <div id="popup" class="popup">
            <div class="popup-content">
                <button class="close-button" onclick="hidePopup()">×</button>
                <h2>Adicionar novo Produto</h2>
                <form id="menu-form" method="post" action="">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" required maxlength="50">

                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description" rows="3" required></textarea>

                    <label for="price">Preço:</label>
                    <input type="number" id="price" name="price" min="0.00" max="100.00" step="0.01" required />

                    <label for="quantity">Quantidade:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required />

                    <label for="fornecedor">Fornecedor:</label>
                    <select id="fornecedor" name="fornecedor" required>
                        <option value="1">Fornecedor 1</option>
                        <option value="2">Fornecedor 2</option>
                        <!-- Adicione mais fornecedores conforme necessário -->
                    </select>

                    <label for="validade">Validade:</label>
                    <input type="date" id="validade" name="validade" required />
                    
                    <button type="submit">Adicionar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'flex';
        }

        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        document.getElementById('menu-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita o envio do formulário

            // Obtém os valores dos campos do formulário
            const name = document.getElementById('name').value;
            const description = document.getElementById('description').value;
            const price = document.getElementById('price').value;
            const quantity = document.getElementById('quantity').value;
            const fornecedor = document.getElementById('fornecedor').value;
            const validade = document.getElementById('validade').value;

            // Envia os dados para o PHP
            const formData = new FormData();
            formData.append('name', name);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('quantity', quantity);
            formData.append('fornecedor', fornecedor);
            formData.append('validade', validade);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Aqui você pode adicionar o código para atualizar a página ou exibir uma mensagem de sucesso.
                alert('Produto adicionado com sucesso!');
                hidePopup();
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
    </script>

</body>
</html>

<?php include 'footer.php'; ?>
