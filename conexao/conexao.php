<?php

// BD REMOTO
$host = '144.22.157.228';
$db = 'Bravo';
$user = 'Bravo';
$pass = 'Bravo';

//BD LOCAL
// $host = 'localhost';
// $db = 'bravo';
// $user = 'root';
// $pass = 'P@$$w0rd';

$port = '3306';

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset"; // qual driver de BD será usado, no caso (mysql)

// criando a conexão com o BD através do PDO 
try { // tratamento de exceções, erros


    $pdo = new PDO($dsn, $user, $pass); // criando um objeto

} catch (PDOException $e) { // se houver erro vai ser capturado pelo catch e será feito o tratamento pelo PDO, se houver erro sera enviado para $e

    echo "Erro ao tentar conectar o banco de dados <p> " . $e;
}
?>