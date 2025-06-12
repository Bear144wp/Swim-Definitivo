<?php
// Inicia a sessão pra saber se o usuário tá logado
session_start();

// Se não tiver logado (sem ID na sessão), volta pro index
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Puxa o arquivo de conexão com o banco de dados
include("conexao.php");

// ===== PEGANDO OS DADOS QUE VIERAM DO FORMULÁRIO =====

// Pega o ID do carro que a gente vai editar e força pra número com intval()
// Isso é uma medida de segurança simples contra gente tentando enviar código malicioso
$id_carro = intval($_POST['id']);

// Pega os outros campos que o usuário preencheu no formulário
$modelo = $_POST['modelo'];
$placa = $_POST['placa'];
$ano = intval($_POST['ano']); // Força pra número também, já que "ano" deve ser numérico

// ===== ATUALIZANDO O REGISTRO NO BANCO DE DADOS =====

// Aqui é onde realmente fazemos o UPDATE no banco
// Só vai atualizar o carro se o ID do carro for esse e ele for do usuário logado
// Isso garante que o cara não vai editar carro de outro usuário
$sql = "UPDATE carros 
        SET modelo = '$modelo', placa = '$placa', ano = $ano 
        WHERE id = $id_carro AND id_usuario = " . $_SESSION['id'];

// Executa o SQL. Se der certo, redireciona de volta pra tela "meus carros"
if ($conn->query($sql) === TRUE) {
    header("Location: meus_carros.php");
} else {
    // Se der erro, mostra o erro na tela (normalmente por problema na conexão ou SQL mal montado)
    echo "Erro ao atualizar carro: " . $conn->error;
}
?>
