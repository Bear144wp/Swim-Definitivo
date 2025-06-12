<?php
// Inicia a sessão para poder acessar e encerrar os dados da sessão atual
session_start();

// Destroi todos os dados da sessão atual (ou seja, "desloga" o usuário)
session_destroy();

// Redireciona o usuário de volta para a página de login (index.php)
header("Location: index.php");

// Garante que o script pare de rodar depois do redirecionamento
exit;
?>