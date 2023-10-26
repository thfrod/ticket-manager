<?php
session_start();

if (!isset($_SESSION['admin_logado'])) {
    //isset, se não estiver definido um adm logado redireciona(header) para a página de login
    header("Location: ./administrador/login.php"); // header direciona para outro lugar
    exit();
} else {
    header("Location: ./administrador/painel_admin.php");
}