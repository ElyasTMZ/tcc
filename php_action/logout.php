<?php
session_start();
session_destroy(); // Encerra a sessão
header('Location: /Tcc/login.php'); // Redireciona para a página de login
exit();
?>
