<?php

$title = 'Painel';
require_once("../shared/head.php");

session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false ) {
    //isset, se não estiver definido um adm logado redireciona(header) para a página de login
    header("Location:login.php"); // header direciona para outro lugar
    exit();
}

require_once('../conexao/conexao.php');

try {
    $stmt = $pdo->prepare("SELECT count(*) as QTD_PRODUTOS FROM PRODUTO");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS
    // echo json_encode($data);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<body class="painel-admin-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <h2>Painel</h2>

        <div class="card" style="width: 18rem;">
            <div class="card-body d-flex gap-4">
                <div
                    class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>

                <div>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Produtos em estoque</h6>
                    <h5 class="card-title">
                        <?php echo $data[0]['QTD_PRODUTOS']; ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</body>