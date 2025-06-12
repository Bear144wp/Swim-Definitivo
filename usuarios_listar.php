<?php
session_start(); // Inicia a sessão pra poder acessar os dados do usuário logado

// Verifica se o usuário está logado e se é um admin. Se não for, volta pro login.
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin') {
    header("Location: index.php");
    exit;
}

include("conexao.php"); // Conecta com o banco de dados

// Consulta que pega todos os usuários do sistema, ordenando por nome
$sql = "SELECT * FROM usuarios ORDER BY nome ASC";
$result = $conn->query($sql); // Executa a query
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários Cadastrados</title>
    <link rel="stylesheet" href="estilo.css"> <!-- Link do CSS para deixar visual bonito -->
</head>
<body>
<div class="container">
    <h2>Usuários Cadastrados</h2>

    <!-- Se encontrou algum usuário no banco, monta a tabela -->
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>

            <!-- Loop pra mostrar cada usuário encontrado -->
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["nome"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo ucfirst($row["tipo"]); ?></td> <!-- Deixa a primeira letra maiúscula -->

                    <td>
                        <!-- Link para editar o tipo do usuário -->
                        <a href="editar_usuario.php?id=<?php echo $row['id']; ?>">Editar</a> |

                        <!-- Link para excluir o usuário com confirmação -->
                        <a href="excluir_usuario.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                            Excluir
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p> <!-- Mensagem se não tiver nenhum usuário -->
    <?php endif; ?>

    <!-- Botão pra voltar pro painel do admin -->
    <br><a href="painel_admin.php" class="btn">Voltar ao Painel</a>
</div>
</body>
</html>

