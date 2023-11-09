<?php

$title = 'Cadastro de Produto';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE CATEGORIA SET CATEGORIA_ATIVO = 0 WHERE CATEGORIA_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $mensagem = "Categoria inativada com sucesso!";
        } else {
            $mensagem = "Erro ao inativar o categoria. Tente novamente.";
        }
    } catch (PDOException $e) {
        $mensagem = "Erro: " . $e->getMessage();
    }
}
?>

<body>

</body>

</html>

<body class="cadastrar-produto-content d-flex">
    <?php require("../shared/aside.php") ?>

    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Inativar categoria</h2>
            <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
        </div>
        <p>
            <?php echo $mensagem; ?>
        </p>
        <a href="listar_categorias.php">Voltar à Lista de categorias</a>
    </div>
</body>