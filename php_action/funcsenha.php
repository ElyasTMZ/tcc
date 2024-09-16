<?php
include_once 'db.php'; // Incluindo o arquivo de conexão

// Função para criar um hash da senha
function criarHashSenha($senha) {
    return password_hash($senha, PASSWORD_BCRYPT);
}

// Função para verificar a senha
function verificarSenha($senha, $hash) {
    return password_verify($senha, $hash);
}

// Função para registrar um novo usuário
function registrarUsuario($nome, $email, $telCelular, $senha) {
    global $pdo; // Usando a conexão PDO global

    $hashSenha = criarHashSenha($senha);

    $sql = "INSERT INTO tbUsuarios (nome, email, telCelular, senha) VALUES (:nome, :email, :telCelular, :senha)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telCelular', $telCelular);
    $stmt->bindParam(':senha', $hashSenha);
    
    return $stmt->execute(); // Retorna true se a execução for bem-sucedida
}

// Função para autenticar um usuário
function autenticarUsuario($email, $senha) {
    global $pdo; // Usando a conexão PDO global

    $sql = "SELECT senha FROM tbUsuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado) {
        return verificarSenha($senha, $resultado['senha']);
    }
    
    return false; // Usuário não encontrado
}
?>
