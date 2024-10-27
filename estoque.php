<?php
include_once 'php_action/db.php'; // Inclui a conexão com o banco de dados
include 'php_action/inserir.php'; // Inclui a função de inserir produto
include 'php_action/editar_produto.php'; // Inclui a função de editar produto
include 'php_action/excluir_produto.php'; // Inclui a função de excluir produto

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
        $nome = $_POST['name'];
        $descricao = $_POST['description'];
        $valor = $_POST['price'];
        $quantidade = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $validade = $_POST['validade'];

        // Verifica se o produto já existe
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM tbProdutos WHERE descricao = :descricao");
        $stmtCheck->bindParam(':descricao', $descricao);
        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() == 0) { // Se não existir, insere
            $dataEntrada = date('Y-m-d');
            $horaEntrada = date('H:i:s');
            inserirProduto($nome, $descricao, $quantidade, $valor, $validade, $dataEntrada, $horaEntrada);
            echo "Produto adicionado com sucesso!";
        } else {
            echo "Produto já existe.";
        }
    } elseif (isset($_POST['codProd'])) {
        // Atualiza o produto
        $codProd = $_POST['codProd'];
        $nome = $_POST['name'];
        $descricao = $_POST['description'];
        $quantidade = intval($_POST['quantity']);
        $valor = $_POST['price'];
        $validade = $_POST['validade'];
        editarProduto($codProd, $nome, $descricao, $quantidade, $valor, $validade);
        echo "Produto atualizado com sucesso!";
    } elseif (isset($_POST['delete'])) {
        // Exclui o produto
        $codProd = intval($_POST['codProd']);
        if (excluirProduto($codProd)) {
            echo "Produto excluído com sucesso!";
        } else {
            echo "Erro ao excluir produto.";
        }
        exit; // Para não continuar processando
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

// Busca todos os produtos do banco de dados
$stmt = $pdo->query("SELECT * FROM tbProdutos");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link rel="stylesheet" href="_css/estoque.css">
</head>
<body class="estoque-page">
    <div class="content">
        <div style="flex: 1; margin-right: 20px;">
            <h1>Estoque</h1>
            <button class="add-button" onclick="showPopup()">Adicionar novo Produto</button>
            <a href="dashboard.php" class="back-button">Voltar</a> <!-- Botão Voltar -->

            <form id="product-form">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Quantidade</th>
                            <th>Validade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($produtos) > 0): ?>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                                    <td>R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['validade']); ?></td>
                                    <td>
                                        <button type="button" onclick="editProduct(<?php echo $produto['codProd']; ?>)">Editar</button>
                                        <button type="button" onclick="deleteProduct(<?php echo $produto['codProd']; ?>)">Excluir</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Nenhum produto encontrado no estoque.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Popup para adicionar/editar item -->
        <div id="popup" class="popup">
            <div class="popup-content">
                <button class="close-button" onclick="hidePopup()">×</button>
                <h2 id="popup-title">Adicionar novo Produto</h2>
                <form id="menu-form">
                    <input type="hidden" id="codProd" name="codProd">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" required maxlength="50">

                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description" rows="3" required></textarea>

                    <label for="price">Preço:</label>
                    <input type="number" id="price" name="price" min="0.00" step="0.01" required />

                    <label for="quantity">Quantidade:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required />

                    <label for="validade">Validade:</label>
                    <input type="date" id="validade" name="validade" required />
                    
                    <button type="submit">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript permanece o mesmo
    </script>
</body>
</html>

<script>
    function showPopup() {
        document.getElementById('popup').style.display = 'flex';
        document.getElementById('popup-title').innerText = 'Adicionar novo Produto';
        document.getElementById('menu-form').reset(); // Limpa o formulário
        document.getElementById('codProd').value = ''; // Reseta o codProd
    }

    function hidePopup() {
        document.getElementById('popup').style.display = 'none';
    }

    function editProduct(codProd) {
        fetch(`php_action/buscar_produto.php?codProd=${codProd}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('name').value = data.nome;
                    document.getElementById('description').value = data.descricao;
                    document.getElementById('quantity').value = data.quantidade;
                    document.getElementById('price').value = data.valor;
                    document.getElementById('validade').value = data.validade;
                    document.getElementById('codProd').value = codProd;
                    document.getElementById('popup-title').innerText = 'Editar Produto';
                    showPopup();
                } else {
                    alert('Produto não encontrado.');
                }
            })
            .catch(error => console.error('Erro ao buscar produto:', error));
    }

    function deleteProduct(codProd) {
        if (confirm("Tem certeza que deseja excluir este produto?")) {
            const formData = new FormData();
            formData.append('delete', true);
            formData.append('codProd', codProd);

            fetch('php_action/excluir_produto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    location.reload(); // Atualiza a página para refletir as mudanças
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
    }

    document.getElementById('menu-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita o envio do formulário

        const name = document.getElementById('name').value;
        const description = document.getElementById('description').value;
        const price = document.getElementById('price').value;
        const quantity = document.getElementById('quantity').value;
        const validade = document.getElementById('validade').value;
        const codProd = document.getElementById('codProd').value;

        const formData = new FormData();
        formData.append('name', name);
        formData.append('description', description);
        formData.append('price', price);
        formData.append('quantity', quantity);
        formData.append('validade', validade);
        formData.append('codProd', codProd);

        fetch('estoque.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Mostra a mensagem de sucesso ou erro
            location.reload(); // Atualiza a página
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    });
</script>
