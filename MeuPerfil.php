<?php
session_start();
include_once 'php_action/db.php';
include_once 'header.php'; // Inclui o cabeçalho

if (isset($_SESSION['codUsu'])) {
    $codUsu = $_SESSION['codUsu'];

    // Busca os dados do usuário
    $sql = "SELECT nome, email, telCelular, foto FROM tbUsuarios WHERE codUsu = :codUsu";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codUsu', $codUsu);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telCelular = $_POST['telCelular'];

        // Verifica se o e-mail já está sendo utilizado por outro usuário
        $sql = "SELECT codUsu FROM tbUsuarios WHERE email = :email AND codUsu != :codUsu";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':codUsu', $codUsu);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $erro = "Este e-mail já está em uso por outro usuário.";
        } else {
            // Inicializa a parte da consulta SQL para atualização
            $sql = "UPDATE tbUsuarios SET nome = :nome, email = :email, telCelular = :telCelular";
            $foto = null;
            $bindParams = [
                ':nome' => $nome,
                ':email' => $email,
                ':telCelular' => $telCelular,
                ':codUsu' => $codUsu,
            ];

            // Processa o upload da foto
            if (!empty($_FILES['foto']['name'])) {
                // Define o caminho para salvar a imagem
                $pasta = 'uploads/';
                $foto = $pasta . basename($_FILES['foto']['name']);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

                // Verifica se a imagem é válida
                $check = getimagesize($_FILES['foto']['tmp_name']);
                if ($check === false) {
                    $erro = "O arquivo enviado não é uma imagem. Por favor, selecione uma imagem válida.";
                    $uploadOk = 0;
                }

                // Permite apenas certos formatos de imagem
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $erro = "Formato inválido! Aceitamos apenas JPG, JPEG, PNG e GIF. Por favor, escolha um desses formatos.";
                    $uploadOk = 0;
                }

                // Limita o tamanho do arquivo
                if ($_FILES['foto']['size'] > 500000) {
                    $erro = "O arquivo é muito grande! O tamanho máximo permitido é de 500 KB. Tente um arquivo menor.";
                    $uploadOk = 0;
                }

                // Se tudo estiver ok, tenta fazer o upload
                if ($uploadOk) {
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto)) {
                        // Atualiza o caminho da foto no banco de dados
                        $sql .= ", foto = :foto";
                        $bindParams[':foto'] = $foto; // Adiciona a foto aos parâmetros de ligação

                        // Atualiza a sessão com a nova foto
                        $_SESSION['user_photo'] = $foto; // Armazena o caminho da nova foto na sessão
                    } else {
                        $erro = "Houve um erro ao fazer o upload da sua foto. Tente novamente.";
                    }
                }
            }

            // Finaliza a consulta SQL
            $sql .= " WHERE codUsu = :codUsu";
            $stmt = $pdo->prepare($sql);

            // Vincula os parâmetros
            foreach ($bindParams as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            if ($stmt->execute()) {
                $sucesso = "Dados atualizados com sucesso!";
                // Atualiza os dados da sessão
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['foto'] = $foto ?? $usuario['foto']; // Mantém a foto anterior se não foi atualizada
            } else {
                $erro = "Erro ao atualizar os dados. Tente novamente.";
            }
        }
    }
} else {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="_css/MeuPerfil.css">
</head>
<body>
    <div class="form-container">
        <form action="MeuPerfil.php" method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label for="telCelular">Telefone Celular:</label>
            <input type="text" id="telCelular" name="telCelular" value="<?= htmlspecialchars($usuario['telCelular']) ?>" required>

            <label for="foto">Foto (opcional):</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <small>Formato permitido: JPG, JPEG, PNG, GIF. Tamanho máximo: 500 KB.</small>

            <button type="submit">Atualizar</button>
        </form>

        <?php
        // Exibe mensagens de erro ou sucesso
        if (!empty($erro)) {
            echo "<p style='color:red;'>$erro</p>";
        }

        if (!empty($sucesso)) {
            echo "<p style='color:green;'>$sucesso</p>";
        }
        ?>
        
        <!-- Exibe a foto atual se existir -->
        <?php if (!empty($usuario['foto'])): ?>
            <h3>Foto Atual:</h3>
            <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto do usuário" style="max-width: 200px;">
        <?php endif; ?>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>