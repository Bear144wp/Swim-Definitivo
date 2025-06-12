<?php
// Começa a sessão pra acessar os dados de quem tá logado
session_start();

// Verifica se o usuário está logado e se é do tipo "mecânico"
// Se não for, redireciona de volta pro index (login)
// Isso impede que usuários não autorizados concluam agendamentos de outros
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'mecanico') {
    header("Location: index.php");
    exit;
}

// Conecta com o banco de dados
include("conexao.php");

// Agora vamos verificar se veio um ID na URL via método GET
// Isso acontece quando o mecânico clica em "Concluir" na lista de agendamentos
if (isset($_GET['id'])) {
    // Pega o ID enviado pela URL e força ele a ser número (evita tentativa de código malicioso)
    $id = intval($_GET['id']);

    // Prepara o comando SQL pra atualizar o status do agendamento pra "concluído"
    // Isso é basicamente marcar que o serviço foi finalizado
    $sql = "UPDATE agendamentos SET status = 'concluido' WHERE id = $id";

    // Executa a atualização no banco
    $conn->query($sql);
}

// Depois de concluir, redireciona o mecânico de volta pra página que lista os agendamentos recebidos
// Isso evita que ele veja a página "em branco" depois de concluir
header("Location: agendamentos_recebidos.php");
