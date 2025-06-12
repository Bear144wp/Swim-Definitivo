<?php
// Começa a sessão pra poder acessar os dados do usuário logado
session_start();

// Se o cara não tiver logado, já manda ele pro index (login)
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Conexão com o banco de dados
include("conexao.php");

// Aqui a gente confere se o formulário foi enviado via método POST
// Isso garante que o acesso veio da submissão correta, e não digitando direto na URL
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pega o ID do agendamento que vai ser editado e força ele a ser número (por segurança)
    $id = intval($_POST['id']);

    // Pega os dados novos do formulário e já faz um tratamento básico contra SQL Injection
    // real_escape_string impede que alguém tente burlar a consulta com comandos maliciosos
    $data = $conn->real_escape_string($_POST['data']);
    $descricao = $conn->real_escape_string($_POST['descricao']);

    // Pega o ID do usuário logado na sessão
    $id_usuario = $_SESSION['id'];

    // Agora essa consulta é pra garantir que:
    // 1. Esse agendamento ainda é pendente (não pode editar depois de concluído)
    // 2. O agendamento realmente pertence ao carro desse usuário (evita mexer no que não é dele)
    $sql = "SELECT a.*, c.id_usuario 
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            WHERE a.id = $id AND c.id_usuario = $id_usuario AND a.status = 'pendente'";

    $result = $conn->query($sql);

    // Se não achou nada, ou o agendamento não é do usuário, ou já foi concluído
    if ($result->num_rows === 0) {
        echo "Agendamento não encontrado, já concluído ou você não tem permissão para editar.";
        exit;
    }

    // Se passou pela verificação, agora faz o UPDATE no banco com a nova data e descrição
    $update = "UPDATE agendamentos 
               SET data = '$data', descricao = '$descricao' 
               WHERE id = $id";

    // Se deu certo, redireciona o usuário de volta para a lista de agendamentos
    if ($conn->query($update) === TRUE) {
        header("Location: meus_agendamentos.php");
        exit;
    } else {
        // Se der algum erro ao atualizar, mostra o erro do banco
        echo "Erro ao atualizar o agendamento: " . $conn->error;
    }

} else {
    // Se alguém tentou acessar essa página sem ser via POST, mostra que tá errado
    echo "Requisição inválida.";
}
?>
