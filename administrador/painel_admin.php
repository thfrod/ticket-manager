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

    // NÚMERO DE PRODUTOS ATIVOS
    $stmt = $pdo->prepare("SELECT count(*) as QTD_PRODUTOS FROM PRODUTO WHERE PRODUTO_ATIVO = 1");
    $stmt->execute();
    $dataProdutosAtivos = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS

    // PEDIDOS REALIZADOS
    $stmt = $pdo->prepare("SELECT count(*) as QTD_PEDIDOS FROM PEDIDO");
    $stmt->execute();
    $dataPedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TOTAL DE TICKETS VENDIDOS
    $stmt = $pdo->prepare("SELECT sum(ITEM_QTD) as TOTAL_TICKETS_VENDIDOS FROM PEDIDO_ITEM");
    $stmt->execute();
    $dataTotalTicketsVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TOTAL DE FATURAMENTO
    $stmt = $pdo->prepare("SELECT sum(ITEM_PRECO) as TOTAL_FATURAMENTO FROM PEDIDO_ITEM");
    $stmt->execute();
    $dataTotalFaturamento = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TOTAL DE USUÁRIOS
    $stmt = $pdo->prepare("SELECT count(*) as TOTAL_USUARIOS FROM USUARIO");
    $stmt->execute();
    $dataTotalUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // PRODUTOS MAIS VENDIDOS
    $stmt = $pdo->prepare(  "SELECT ped.PRODUTO_ID, GROUP_CONCAT(PRODUTO_IMAGEM.IMAGEM_URL), prod.PRODUTO_NOME, SUM(ped.ITEM_QTD) AS 'TOTAL_VENDIDO' 
                            FROM PEDIDO_ITEM ped INNER JOIN PRODUTO prod
                            ON ped.PRODUTO_ID = prod.PRODUTO_ID
                            INNER JOIN PRODUTO_IMAGEM
                            ON ped.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID
                            GROUP BY PRODUTO_ID 
                            ORDER BY `TOTAL_VENDIDO` DESC;");
    $stmt->execute();
    $produtosMaisVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // SELECT ped.PRODUTO_ID, GROUP_CONCAT(PRODUTO_IMAGEM.IMAGEM_URL), prod.PRODUTO_NOME, SUM(ped.ITEM_QTD) AS 'TOTAL_VENDIDO' 
    // FROM PEDIDO_ITEM ped INNER JOIN PRODUTO prod
    // ON ped.PRODUTO_ID = prod.PRODUTO_ID
    // INNER JOIN PRODUTO_IMAGEM
    // ON ped.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID
    // GROUP BY PRODUTO_ID 
    // ORDER BY `TOTAL_VENDIDO` DESC;
    




    // echo json_encode($data);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<body class="painel-admin-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <h2>Painel administrador</h2><br>

        <div class="card" style="width: 18rem;">
            <div class="card-body d-flex gap-4">
                <div
                    class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>

                <div>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Produtos ativos em estoque</h6>
                    <h5 class="card-title">
                        <?php echo $dataProdutosAtivos[0]['QTD_PRODUTOS']; ?>
                    </h5>
                </div>
            </div>
        </div><br>

        <div class="card" style="width: 18rem;">
            <div class="card-body d-flex gap-4">
                <div
                    class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>

                <div>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Pedidos realizados</h6>
                    <h5 class="card-title">
                        <?php echo $dataPedidos[0]['QTD_PEDIDOS']; ?>
                    </h5>
                </div>
            </div>
        </div><br>

        <div class="card" style="width: 18rem;">
            <div class="card-body d-flex gap-4">
                <div
                    class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>

                <div>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Total de tickets vendidos</h6>
                    <h5 class="card-title">
                        <?php echo $dataTotalTicketsVendidos[0]['TOTAL_TICKETS_VENDIDOS']; ?>
                    </h5>
                </div>
            </div>
        </div><br>

        <div class="card" style="width: 18rem;">
            <div class="card-body d-flex gap-4">
                <div
                    class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>

                <div>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Faturamento total</h6>
                    <h5 class="card-title">
                        <?php echo "R$ " . $dataTotalFaturamento[0]['TOTAL_FATURAMENTO']; ?>
                    </h5>
                </div>
            </div>
        </div><br>

        <div class="card" style="width: 18rem;">
            <div class="card-body d-flex gap-4">
                <div
                    class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                    <span class="material-symbols-outlined text-white">shopping_cart</span>
                </div>

                <div>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Nº de usuários cadastrados</h6>
                    <h5 class="card-title">
                        <?php echo $dataTotalUsuarios[0]['TOTAL_USUARIOS']; ?>
                    </h5>
                </div>
            </div>
        </div>
        <br><br>
        <h6 class="card-subtitle mb-2 text-body-secondary">Produtos mais vendidos</h6>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Imagem</th>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center">Unidades vendidas</th>
                </tr>
            </thead>
            <tbody>
                
                <?php //var_dump($produtosMaisVendidos); 
                foreach ($produtosMaisVendidos as $produto): ?>
                    <tr>
                        <td class="text-center">
                            <img src="<?php echo strtok($produto['GROUP_CONCAT(PRODUTO_IMAGEM.IMAGEM_URL)'], ','); ?>" alt="Imagem do Produto" width="50">
                        </td>
                        <td class="text-center">
                            <?php echo $produto['PRODUTO_NOME']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $produto['TOTAL_VENDIDO']?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br><br><br>


    </div>
</body>