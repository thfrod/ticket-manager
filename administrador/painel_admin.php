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

<body class="painel-admin-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <h2>Painel</h2>

    </div>
</body>