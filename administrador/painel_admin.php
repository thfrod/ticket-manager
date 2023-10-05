<?php

session_start();

if(!isset($_SESSION['admin_logado'])) {
    //isset, se não estiver definido um adm logado redireciona(header) para a página de login
    header("Location:login.php"); // header direciona para outro lugar
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Admnistrador</title>
</head>
<body>
    <h2>Bem vindo Admnistrador</h2>
    <a href="cadastrar_produto.php">
    <button>Cadastrar Produto</button></a>

    <a href="listar_produtos.php">
        <button>Listar produtos</button>
    </a>
</body>
</html>