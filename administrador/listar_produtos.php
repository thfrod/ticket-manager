<?php

$title = 'Listagem de produtos';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

try {
    $stmt = $pdo->prepare("SELECT * FROM PRODUTO");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>

<body class="listar-produtos-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Lista de Produtos</h2>
            <div>
                <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
                <a href="cadastrar_produto.php" class="btn btn-primary">Adicionar produto</a>
            </div>
        </div>

        <table class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th scope="col" class="text-center">ID</th>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center desc">Descrição</th>
                    <th scope="col" class="text-center">Preço</th>
                    <!-- <th scope="col" class="text-center">Imagem</th> -->
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($produtos as $produto): ?>
                    <tr>

                        <td class="text-center">
                            <?php echo $produto['PRODUTO_ID']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $produto['PRODUTO_NOME']; ?>
                        </td>
                        <td class="text-center desc">
                            <?php echo $produto['PRODUTO_DESC']; ?>
                        </td>
                        <td class="text-center">
                            R$
                            <?php echo $produto['PRODUTO_PRECO']; ?>
                        </td>
                        <!-- <td><img src="<?php #echo $produto['imagem']; ?>" alt="Imagem do Produto" width="50"></td> -->
                        <td class="text-center">
                            <a href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="btn btn-primary">

                                <img src="../assets/imgs/icons/edit.svg" alt="">
                            </a>
                            <a href="excluir_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="btn btn-danger">
                                <img src="../assets/imgs/icons/trash.svg" alt="">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>