<?php

$title = 'Cadastro de Produto';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false ) {
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
            header("Location:listar_categorias.php?success=Categoria excluída com successo");
        } else {
            header("Location:listar_categorias.php?error=Erro ao excluír categoria");
        }
    } catch (PDOException $e) {
        header("Location:listar_categorias.php?error=Erro ao excluír categoria");
    }
} else {
    header("Location:listar_categorias.php?error=Erro ao excluír categoria");
}
?>