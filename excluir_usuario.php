<?php
// Inicia a sessão para acessar dados do usuário logado
session_start();

// Verifica se o usuário está logado e se é um administrador
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    // Se não for admin ou não estiver logado, redireciona para a página inicial
    header("Location: index.php");
    exit;
}

// Conecta com o banco de dados
include("conexao.php");

// Verifica se foi enviado um ID via URL (GET)
if (isset($_GET['id'])) {
    // Converte o ID para número inteiro (ajuda a evitar ataques e erros)
    $id = intval($_GET['id']);

    // Previne que o admin exclua a própria conta (muito importante)
    if ($_SESSION['id'] == $id) {
        echo "Você não pode excluir sua própria conta.";
        exit;
    }

    // Executa a exclusão do usuário com base no ID passado
    $conn->query("DELETE FROM usuarios WHERE id = $id");

    // Depois de excluir, redireciona para a lista de usuários
    header("Location: usuarios_listar.php");
}
?>

