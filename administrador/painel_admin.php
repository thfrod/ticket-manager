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

    // NÚMERO DE CATEGORIAS ATIVAS
    $stmt = $pdo->prepare("SELECT count(*) as QTD_CATEGORIA FROM CATEGORIA WHERE CATEGORIA_ATIVO = 1");
    $stmt->execute();
    $dataCategoriasAtivas = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS

    // NÚMERO DE ADMINISTRADORES ATIVOS
    $stmt = $pdo->prepare("SELECT count(*) as QTD_ADMINS FROM ADMINISTRADOR WHERE ADM_ATIVO = 1");
    $stmt->execute();
    $dataAdministradoresAtivos = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS

    // TOTAL DE PRODUTOS EM ESTOQUE
    $stmt = $pdo->prepare("SELECT sum(PRODUTO_QTD) as QTD_ESTOQUE FROM PRODUTO_ESTOQUE");
    $stmt->execute();
    $dataProdutosEmEstoque = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS

    // ÚLTIMOS PRODUTOS ADICIONADOS
    $stmt = $pdo->prepare(  "SELECT p.PRODUTO_ID, pi.PRIMEIRA_IMAGEM, p.PRODUTO_NOME
                            FROM PRODUTO p
                            INNER JOIN
                            (
                                SELECT PRODUTO_ID, MIN(IMAGEM_ORDEM), MIN(IMAGEM_URL) as PRIMEIRA_IMAGEM
                                FROM PRODUTO_IMAGEM
                                GROUP BY PRODUTO_ID
                            ) pi ON p.PRODUTO_ID = pi.PRODUTO_ID
                            ORDER BY p.PRODUTO_ID DESC;");
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

        <div class="cards">

            <div class="card">
                <div class="card-body d-flex gap-4">
                    <div
                        class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                        <span class="material-symbols-outlined text-white">inventory_2</span>
                    </div>

                    <div>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Produtos<br>ativos</h6>
                        <h5 class="card-title">
                            <?php echo $dataProdutosAtivos[0]['QTD_PRODUTOS']; ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex gap-4">
                    <div
                        class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                        <span class="material-symbols-outlined text-white">confirmation_number</span>
                    </div>

                    <div>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Tickets<br>em estoque</h6>
                        <h5 class="card-title">
                            <?php echo $dataProdutosEmEstoque[0]['QTD_ESTOQUE']; ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex gap-4">
                    <div
                        class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                        <span class="material-symbols-outlined text-white">category</span>
                    </div>

                    <div>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Categorias<br>ativas</h6>
                        <h5 class="card-title">
                            <?php echo $dataCategoriasAtivas[0]['QTD_CATEGORIA']; ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex gap-4">
                    <div
                        class="icon-wrapper rounded-circle bg-primary p-3 d-flex justify-content-center align-items-center">
                        <span class="material-symbols-outlined text-white">people</span>
                    </div>

                    <div>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Administradores<br>ativos</h6>
                        <h5 class="card-title">
                            <?php echo $dataAdministradoresAtivos[0]['QTD_ADMINS']; ?>
                        </h5>
                    </div>
                </div>
            </div>            
        </div>

        <br>

        
        <h5 class="card-subtitle mb-3 text-body-secondary">Últimos produtos adicionados</h5>
        
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col" class="text-center">Imagem</th>
                        <th scope="col" class="text-center">Nome</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php //var_dump($produtosMaisVendidos); 
                    $i = 10;
                    foreach ($produtosMaisVendidos as $produto): 
                    
                        if($i > 0) {
                            $i--;
                        } else {
                            break;
                        }

                        ?>
                        <tr>
                            <td class="text-center">
                                <?php 
                                    echo "#" . $produto['PRODUTO_ID'];
                                ?>
                            </td>
                            <td class="text-center">
                                <img src="<?php echo $produto['PRIMEIRA_IMAGEM']; ?>" alt="Imagem do Produto" width="50">
                            </td>
                            <td class="text-center">
                                <?php echo $produto['PRODUTO_NOME']; ?>
                            </td>
                            <td class="text-center actions">
                                <div>
                                    <a href="ver_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="btn btn-primary">
                                        <img src="../assets/imgs/icons/eye.svg" alt="">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        endforeach; ?>
                </tbody>
            </table>

    </div>
</body>