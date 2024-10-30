<?php
include_once 'db.php'; // Inclui a conexão com o banco de dados
include_once 'funcsenha.php'; // Inclui as funções relacionadas às senhas

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telCelular = trim($_POST['telCelular']);
    $senha = $_POST['senha'];

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($email) && !empty($telCelular) && !empty($senha)) {
        // Tenta registrar o usuário
        if (registrarUsuario($nome, $email, $telCelular, $senha)) {
            header('Location: login.php'); // Redireciona para a página de login após o cadastro
            exit();
        } else {
            $erro = "Erro ao registrar o usuário";
        }
    } else {
        $erro = "Por favor, preencha todos os campos necessários.";
    }
}
?>
