<?php
include_once 'db.php'; // Inclui a conexão com o banco de dados

// Função para gerar salt
function gerarSalt() {
    return bin2hex(random_bytes(16)); // Gera um salt de 32 caracteres hexadecimais
}

// Função para criar o hash da senha utilizando o salt
function criarHashSenha($senha, $salt) {
    return hash('sha256', $senha . $salt); // Cria um hash da senha com o salt
}

// Função para registrar o usuário no banco de dados
function registrarUsuario($nome, $email, $telCelular, $senha) {
    global $pdo;

    // Gera o salt e cria o hash da senha
    $salt = gerarSalt();
    $hashSenha = criarHashSenha($senha, $salt);

    try {
        // SQL para inserir dados na tabela tbUsuarios
        $sql = "INSERT INTO tbUsuarios (nome, senha, email, telCelular, salt) 
                VALUES (:nome, :senha, :email, :telCelular, :salt)";
        
        // Preparar a declaração
        $stmt = $pdo->prepare($sql);
        
        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':senha', $hashSenha);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telCelular', $telCelular);
        $stmt->bindParam(':salt', $salt);

        // Executar a declaração
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        // Exibir mensagem de erro e retornar false
        echo "Erro ao registrar o usuário: " . $e->getMessage();
        return false;
    }
}

// Função para autenticar um usuário
function autenticarUsuario($email, $senha) {
    global $pdo;

    // Busca o hash da senha e o salt do banco de dados
    $sql = "SELECT senha, salt FROM tbUsuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado) {
        // Verifica se a senha fornecida corresponde ao hash armazenado
        return verificarSenha($senha, $resultado['senha'], $resultado['salt']);
    }
    
    return false; // Usuário não encontrado
}

// Função para verificar a senha
function verificarSenha($senha, $hashArmazenado, $salt) {
    // Cria o hash da senha fornecida usando o salt armazenado
    $hashSenha = criarHashSenha($senha, $salt);
    
    // Compara o hash da senha fornecida com o hash armazenado
    return $hashSenha === $hashArmazenado;
}
?>
