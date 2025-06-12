<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['id'];
$usuario_tipo = $_SESSION['tipo'];
$conversas = [];

if ($usuario_tipo === 'cliente') {
    $sql = "SELECT DISTINCT a.id_mecanico, u.nome 
            FROM agendamentos a
            JOIN usuarios u ON a.id_mecanico = u.id
            JOIN carros c ON a.id_carro = c.id
            WHERE c.id_usuario = $usuario_id";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        if (!empty($row['id_mecanico']) && !empty($row['nome'])) {
            $conversas[] = [
                'id_cliente' => $usuario_id,
                'id_mecanico' => $row['id_mecanico'],
                'nome' => $row['nome']
            ];
        }
    }
} elseif ($usuario_tipo === 'mecanico') {
    $sql = "SELECT DISTINCT c.id_usuario AS id_cliente, u.nome 
            FROM agendamentos a
            JOIN carros c ON a.id_carro = c.id
            JOIN usuarios u ON c.id_usuario = u.id
            WHERE a.id_mecanico = $usuario_id";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        if (!empty($row['id_cliente']) && !empty($row['nome'])) {
            $conversas[] = [
                'id_cliente' => $row['id_cliente'],
                'id_mecanico' => $usuario_id,
                'nome' => $row['nome']
            ];
        }
    }
} else {
    die("Apenas clientes e mecânicos podem ver conversas.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minhas Conversas</title>
  <link rel="stylesheet" href="estilo.css">
  <style>
    ul { list-style: none; padding: 0; }
    li { margin-bottom: 10px; }
    a.btn-link {
        background: #007bff;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }
    a.btn-link:hover {
        background: #0056b3;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Minhas Conversas</h2>

  <?php if (count($conversas) > 0): ?>
    <ul>
      <?php foreach ($conversas as $c): ?>
        <li>
          <a class="btn-link" href="chat.php?id_cliente=<?php echo $c['id_cliente']; ?>&id_mecanico=<?php echo $c['id_mecanico']; ?>">
            Conversar com <?php echo htmlspecialchars($c['nome']); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Nenhuma conversa encontrada.</p>
  <?php endif; ?>

  <br><a href="painel_<?php echo $usuario_tipo; ?>.php" class="btn-link">Voltar ao painel</a>
</div>
</body>
</html>
