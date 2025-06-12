<?php
// Começa a sessão pra saber quem está logado
session_start();

// Se o usuário não estiver logado, manda ele pro login
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Conexão com o banco de dados
include("conexao.php");

// Verifica se o ID do carro foi enviado na URL (via GET)
if (isset($_GET['id'])) {
    // Converte o ID para número inteiro (evita problemas de segurança)
    $id = intval($_GET['id']);

    // Primeiro a gente verifica se esse carro ainda tem agendamentos pendentes ou em andamento
    $check = $conn->query("SELECT * FROM agendamentos WHERE id_carro = $id AND status != 'concluido'");

    // Se encontrou algum agendamento que não foi concluído, não deixa excluir
    if ($check->num_rows > 0) {
        echo "Não é possível excluir este carro porque ele possui agendamentos pendentes ou em andamento.";
        exit;
    }

    // Se não tem mais pendências, pode apagar os agendamentos que já foram concluídos
    $conn->query("DELETE FROM agendamentos WHERE id_carro = $id AND status = 'concluido'");

    // Depois de limpar os agendamentos concluídos, agora sim a gente pode excluir o carro
    $sql = "DELETE FROM carros WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        // Se a exclusão for feita com sucesso, volta pra tela dos carros
        header("Location: meus_carros.php");
        exit;
    } else {
        // Se der erro ao excluir o carro, mostra o erro
        echo "Erro ao excluir o carro: " . $conn->error;
    }

} else {
    // Caso o ID do carro não tenha sido passado na URL
    echo "ID inválido.";
}
?>
