<?php
// Puxa o arquivo de conexão com o banco de dados
// Aqui é onde tá o mysqli_connect com os dados do banco (servidor, usuário, senha, nome do banco)
include("conexao.php");

// Aqui a gente pega os dados que vieram do formulário via método POST
// Ou seja, o usuário preencheu e enviou os campos do formulário, e agora a gente recebe eles aqui
$id_carro = $_POST['id_carro'];       // ID do carro selecionado pelo cliente
$data = $_POST['data'];               // Data que o cliente escolheu pra agendar
$descricao = $_POST['descricao'];     // Descrição do que precisa ser feito

// Já deixamos o status como "pendente" por padrão (toda manutenção começa assim)
$status = 'pendente';

// Monta a consulta SQL pra inserir no banco uma nova manutenção
// INSERT INTO insere os dados na tabela "agendamentos"
// Os valores vêm das variáveis que pegamos do formulário
$sql = "INSERT INTO agendamentos (id_carro, data, descricao, status)
        VALUES ('$id_carro', '$data', '$descricao', '$status')";

// Aqui a gente executa a consulta no banco usando o objeto $conn
// Se der certo, mostra uma mensagem de sucesso na tela
if ($conn->query($sql) === TRUE) {
    echo "Manutenção agendada com sucesso!";
} else {
    // Se der erro, mostra a mensagem de erro na tela com o motivo
    echo "Erro ao agendar: " . $conn->error;
}
?>
