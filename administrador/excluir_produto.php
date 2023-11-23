<?php
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false ) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE PRODUTO SET PRODUTO_ATIVO = 0 WHERE PRODUTO_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location:listar_produtos.php?success=Produto excluído com successo");

        } else {
            header("Location:listar_produtos.php?error=Erro ao excluír produto");
        }
    } catch (PDOException $e) {
        header("Location:listar_produtos.php?error=Erro ao excluír produto");
    }
} else {
    header("Location:listar_produtos.php?error=Erro ao excluír produto");
}
?>