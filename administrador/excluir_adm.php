<?php

$title = 'Excluir ADM';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false ) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("UPDATE ADMINISTRADOR SET ADM_ATIVO = 0 WHERE ADM_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location:listar_adms.php?success=Administrador inativado com successo");
        } else {
            header("Location:listar_adms.php?error=Erro ao inativar administrador");
        }
    } catch (PDOException $e) {
        header("Location:listar_adms.php?error=Erro ao inativar administrador");
    }
} else {
    header("Location:listar_adms.php?error=Erro ao inativar administrador");
}
?>