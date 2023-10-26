<?php

$title = 'Painel';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado'])) {
    //isset, se não estiver definido um adm logado redireciona(header) para a página de login
    header("Location:login.php"); // header direciona para outro lugar
    exit();
}
?>

<body class="my-3">
    <div class="container">
        <h2>Bem vindo</h2>
        <a href="cadastrar_produto.php" class="btn btn-primary">
            Cadastrar Produto
        </a>
        <a href="listar_produtos.php" class="btn btn-primary">
            Listar produtos
        </a>
    </div>
</body>