<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão se ainda não estiver ativa
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Seu site">
    <link rel="stylesheet" href="_css/header.css"> 
</head>
<body>
    <header>
        <div class="logo">
            <img id="logo" alt="Logo" src="image/logoetec.png">
        </div>
        <div class="menu">
            <nav class="links">
                <a href="menu.php">Cardápio</a>
                <a href="meuped.php">Pedidos</a>
                <a href="carrinho.php">Carrinho</a>
                <a href="MeuPerfil.php">Meu Perfil</a>
            </nav>
        </div>
        <div class="agrupamento">
            <div class="user-info">
                <?php if (isset($_SESSION['foto']) && !empty($_SESSION['foto'])): ?>
                    <img src="<?php echo $_SESSION['foto']; ?>" alt="Foto do Usuário" class="user-photo">
                <?php else: ?>
                    <img src="image/logoetec.png" alt="Logo" class="user-photo"> <!-- Foto padrão -->
                <?php endif; ?>
            </div>
            <div class="logout-button">
                <a href="php_action/logout.php">Sair</a>
        </div>
        </div>
    </header>
</body>
</html>
