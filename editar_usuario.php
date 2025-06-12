<?php
// Inicia a sessão pra saber quem está logado
session_start();

// Verifica se o usuário está logado E se é do tipo 'admin'
// Se não for, manda direto pro login
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Conecta ao banco de dados
include("conexao.php");

// Se o ID do usuário foi passado na URL (usando GET), pega os dados desse usuário no banco
if (isset($_GET['id'])) {
    // Converte pra inteiro pra garantir que não venha código malicioso
    $id = intval($_GET['id']);
    
    // Faz a consulta no banco pra pegar os dados do usuário com esse ID
    $usuario = $conn->query("SELECT * FROM usuarios WHERE id = $id")->fetch_assoc();
}

// Se o formulário foi enviado (ou seja, método POST), atualiza o tipo do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega o ID do usuário e o novo tipo selecionado
    $id = intval($_POST['id']);
    $novoTipo = $_POST['tipo'];

    // Atualiza o tipo do usuário no banco
    $conn->query("UPDATE usuarios SET tipo = '$novoTipo' WHERE id = $id");

    // Depois de atualizar, redireciona de volta pra lista de usuários
    header("Location: usuarios_listar.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Estilo visual -->
</head>
<body>
<div class="container">
    <h2>Editar Tipo de Usuário</h2>

    <!-- Formulário pra atualizar o tipo de conta do usuário -->
    <!-- Envia os dados via POST pra essa mesma página (editar_usuario.php) -->
    <form method="POST" action="editar_usuario.php">
        <!-- Campo escondido com o ID do usuário (pra sabermos qual atualizar) -->
        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

        <!-- Mostra nome e email do usuário -->
        <p><strong>Nome:</strong> <?php echo $usuario['nome']; ?></p>
        <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>

        <!-- Seleção do novo tipo de conta -->
        <label>Tipo de Conta:</label><br>
        <select name="tipo" required>
            <option value="cliente" <?php if ($usuario['tipo'] == 'cliente') echo 'selected'; ?>>Cliente</option>
            <option value="mecanico" <?php if ($usuario['tipo'] == 'mecanico') echo 'selected'; ?>>Mecânica</option>
            <option value="admin" <?php if ($usuario['tipo'] == 'admin') echo 'selected'; ?>>Administrador</option>
        </select><br><br>

        <!-- Botão pra salvar as alterações -->
        <button type="submit">Salvar Alterações</button>
    </form>

    <!-- Link pra voltar pra lista de usuários -->
    <br><a href="usuarios_listar.php" class="btn">Voltar</a>
</div>
</body>
</html>


