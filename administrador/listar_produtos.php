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
    $stmt = $pdo->prepare("SELECT p.PRODUTO_ID,p.PRODUTO_NOME, p.PRODUTO_DESC, p.PRODUTO_PRECO, p.PRODUTO_DESCONTO, p.PRODUTO_ATIVO, pi.IMAGEM_URL, c.CATEGORIA_NOME, pe.PRODUTO_QTD from PRODUTO p inner join PRODUTO_IMAGEM pi on p.PRODUTO_ID = pi.PRODUTO_ID inner join CATEGORIA c on p.CATEGORIA_ID = c.CATEGORIA_ID left outer join PRODUTO_ESTOQUE pe on p.PRODUTO_ID = pe.PRODUTO_ID order by p.PRODUTO_NOME, p.PRODUTO_ATIVO DESC");
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
                    <th scope="col" class="text-center">Imagem</th>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center desc">Descrição</th>
                    <th scope="col" class="text-center">Preço</th>
                    <th scope="col" class="text-center">Desconto</th>
                    <th scope="col" class="text-center">Categoria</th>
                    <th scope="col" class="text-center">Estoque</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td class="text-center">
                            <img src="<?php echo $produto['IMAGEM_URL']; ?>" alt="Imagem do Produto" width="50">
                        </td>
                        <td class="text-center">
                            <?php echo $produto['PRODUTO_NOME']; ?>
                        </td>
                        <td class="text-center desc">
                            <?php 
                                $descricao = $produto['PRODUTO_DESC'];
                                if(strlen($descricao) > 60) $descricao = substr($descricao, 0, 60).'...';
                                echo $descricao; 
                            ?>
                        </td>
                        <td class="text-center">
                            R$
                            <?php echo $produto['PRODUTO_PRECO']; ?>
                        </td>
                        <td class="text-center">
                            R$
                            <?php echo $produto['PRODUTO_DESCONTO']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $produto['CATEGORIA_NOME']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $produto['PRODUTO_QTD'] ? $produto['PRODUTO_QTD'] : 0; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $produto['PRODUTO_ATIVO'] ? 'Ativo' : 'Inativo'; ?>
                        </td>
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
    <?php require_once("../shared/notificacao.php"); ?>
</body>