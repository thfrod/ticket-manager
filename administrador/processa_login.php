<?php

session_start(); //criando uma seção, e pode ser usada em outros arquivos caso queira
require_once('../conexao/conexao.php'); // o arquivo de conexão é requirido e é obrigado a existir

$nome = $_POST['nome']; // a variavel nome vai receber via POST o nome que o usuário digitar o 'nome' está vinculado ao login.php
$senha = $_POST['senha'];
echo $nome, $senha; 
$sql = "SELECT * FROM administrador WHERE ADM_NOME = :nome AND ADM_SENHA = :senha AND ADM_ATIVO = 1"; // quer ver se isso existe no BD, usou ( : ) para vincular com o $query

$query = $pdo->prepare($sql); // variavel que vai receber a consulta sql de cima e o "prepare" vai preparar uma instrução sql para o PDO
// prepare proteje os dados de inajeção de sql, invasões de hacker
$query->bindParam(':nome', $nome, PDO::PARAM_STR); // método que verifica se é uma string
$query->bindParam(':senha', $senha, PDO::PARAM_STR); //bindParam vincula 

$query->execute();

if ($query->rowCount() > 0) { // se a contagem foi maior que 0, significa que o nome existe no BD
    $_SESSION['admin_logado'] = true;
    header('Location: painel_admin.php');

} else {
    header('Location: login.php?erro');
}

?>