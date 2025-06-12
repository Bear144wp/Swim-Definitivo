<?php
// Inicia a sessão PHP — isso permite guardar informações do usuário (como login, nome, tipo, etc.)
// entre diferentes páginas do sistema. Sem isso, não teríamos como "lembrar" quem está logado.
session_start();

// Verifica se o usuário está logado e se é do tipo "cliente"
// $_SESSION é uma variável global que guarda dados salvos durante a sessão
// isset() verifica se a variável existe. Se não tiver ID ou não for cliente, redireciona para o index
if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    header("Location: index.php"); // redireciona para a página inicial
    exit(); // encerra o código aqui mesmo para não executar o restante
}

// Inclui o arquivo "conexao.php", que faz a conexão com o banco de dados MySQL
// Esse arquivo normalmente usa mysqli_connect() com servidor, usuário, senha e nome do banco
include("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <!-- Link do CSS para aplicar o visual personalizado da página -->
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <!-- Conteúdo centralizado -->
    <div class="container">
        <!-- Exibe o nome do cliente que está logado, puxando da sessão iniciada -->
        <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
        <p>Você está logado como <strong>Cliente</strong>.</p>

        <?php
        // Salva o ID do usuário logado (usado para fazer consultas no banco)
        $id_usuario = $_SESSION['id'];
        
        // Variável para armazenar os avisos personalizados para este cliente
        $avisos = "";

        // ===== CONSULTA 1: PRÓXIMA MANUTENÇÃO AGENDADA =====

        // Essa consulta busca a próxima manutenção que o cliente tem marcada no futuro.
        // TABELAS ENVOLVIDAS:
        // - agendamentos: guarda os agendamentos (data, status, mecânico etc)
        // - carros: guarda os carros cadastrados pelos clientes
        // JOIN: junta as tabelas "agendamentos" (alias "a") com "carros" (alias "c") onde o ID do carro bate
        // WHERE:
        //   - c.id_usuario = $id_usuario -> só traz os carros do cliente logado
        //   - a.data >= CURDATE() -> só traz agendamentos futuros (CURDATE() pega a data atual)
        // ORDER BY a.data ASC LIMIT 1 -> pega o mais próximo
        $sqlProxima = "SELECT * FROM agendamentos a
                       JOIN carros c ON a.id_carro = c.id
                       WHERE c.id_usuario = $id_usuario AND a.data >= CURDATE()
                       ORDER BY a.data ASC LIMIT 1";

        // Executa a consulta usando o objeto $conn (criado no arquivo conexao.php)
        // $resProxima agora guarda o resultado da consulta
        $resProxima = $conn->query($sqlProxima);

        // Verifica se veio pelo menos uma linha de resultado
        if ($resProxima->num_rows > 0) {
            // fetch_assoc() pega a linha como array associativo (ex: $dados['data'])
            $prox = $resProxima->fetch_assoc();

            // Formata a data no formato brasileiro (d/m/Y) e adiciona na variável de avisos
            $avisos .= "<p style='color: orange; font-weight: bold;'>📅 Próxima manutenção agendada: " . date('d/m/Y', strtotime($prox['data'])) . "</p>";
        }

        // ===== CONSULTA 2: ÚLTIMO MECÂNICO QUE ATENDEU =====

        // Agora buscamos o nome do último mecânico que atendeu o cliente
        // Essa consulta também junta 3 tabelas:
        // - agendamentos (a)
        // - carros (c)
        // - usuarios (u) — onde estão os dados dos mecânicos
        // WHERE:
        //   - c.id_usuario = $id_usuario -> só os carros do cliente logado
        //   - a.status = 'concluido' -> só manutenções finalizadas
        //   - a.id_mecanico IS NOT NULL -> só se realmente teve mecânico vinculado
        $sqlUltimo = "SELECT a.*, u.nome as mecanico_nome FROM agendamentos a
                      JOIN carros c ON a.id_carro = c.id
                      JOIN usuarios u ON a.id_mecanico = u.id
                      WHERE c.id_usuario = $id_usuario AND a.status = 'concluido' AND a.id_mecanico IS NOT NULL
                      ORDER BY a.data DESC LIMIT 1";

        // Executa a consulta
        $resUltimo = $conn->query($sqlUltimo);

        // Se encontrou algum mecânico
        if ($resUltimo->num_rows > 0) {
            $mec = $resUltimo->fetch_assoc(); // pega o resultado
            // Mostra o nome do mecânico
            $avisos .= "<p style='color: green; font-weight: bold;'>🛠️ Último mecânico que atendeu: " . $mec['mecanico_nome'] . "</p>";
        }

        // Exibe os avisos na tela, abaixo da mensagem de boas-vindas
        echo $avisos;
        ?>

        <!-- Botões de navegação para as funcionalidades do cliente -->
        <!-- Cada botão é um link que leva para outra página -->
        <a href="form_carro.php" class="btn">Cadastrar Carro</a><br><br>
        <a href="meus_carros.php" class="btn">Ver Meus Carros</a><br><br>
        <a href="form_agendar.php" class="btn">Agendar Manutenção</a><br><br>
        <a href="meus_agendamentos.php" class="btn">Ver Meus Agendamentos</a><br><br>
        <a href="relatorio_concluidos.php" class="btn">Manutenções Concluídas</a><br><br>
        <a href="historico_manutencoes.php" class="btn">Histórico de Manutenções</a><br><br>

        <!-- O link abaixo já abre um chat com um mecânico específico (ID 6) -->
        <a href="chat.php?mecanico=6" class="btn">Falar com Mecânico</a><br><br>

        <!-- Faz o logout (encerra a sessão e redireciona para o login) -->
        <a href="logout.php" class="btn">Sair</a>
    </div>
</body>
</html>
