<?php
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados
include 'header.php'; // Inclui o cabeçalho
include 'php_action/inserir.php'; // Inclui a função de inserir produto

if (!isset($_SESSION['email'])) {
    header('Location: /Tcc/login.php'); // Redireciona para a página de login
    exit();
}

// Chama a função de inserir produto se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos necessários foram preenchidos
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
        // Captura os dados do formulário
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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolhas Cantina</title>
    <link rel="stylesheet" href="_css/estoque.css">
</head>
<body class="estoque-page">

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
                <form id="menu-form">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" required maxlength="50">

                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description" rows="3" required></textarea>

                    <label for="price">Preço:</label>
                    <input type="number" id="price" name="price" min="0.00" max="100.00" step="0.01" required />

                    <label for="quantity">Quantidade:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required />

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
            const validade = document.getElementById('validade').value;

            // Envia os dados para o PHP
            const formData = new FormData();
            formData.append('name', name);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('quantity', quantity);
            formData.append('validade', validade);

            fetch('estoque.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Produto adicionado com sucesso!');
                hidePopup();
                // Opcional: Atualizar a lista de produtos
                // document.getElementById('menu').innerHTML += data;
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
    </script>

</body>
</html>
<?php include 'footer.php'; ?>
