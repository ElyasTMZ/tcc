<?php
$host = 'localhost'; // Endereço do servidor MySQL
$dbname = 'dbcantina'; // Nome do banco de dados
$user = 'root'; // Nome de usuário MySQL
$password = ''; // Senha MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'Conexão bem-sucedida!';
} catch (PDOException $e) {
    echo 'Conexão falhou: ' . $e->getMessage();
}
?>
