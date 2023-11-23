<?php
session_start();
$_SESSION['admin_logado'] = false;
$_SESSION = array();
header('Location: painel_admin.php');

?>
