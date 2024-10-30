<?php
$salt = bin2hex(random_bytes(16)); // Gera um salt de 32 caracteres
$senha = 'Admin123';
$senhaHash = hash('sha256', $senha . $salt);

echo "Salt: $salt\n";
echo "Senha Hash: $senhaHash\n";
?>
