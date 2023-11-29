<?php

$title = 'Listagem de categorias';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false ) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

try {
    $stmt = $pdo->prepare("SELECT * FROM CATEGORIA ORDER BY CATEGORIA_ATIVO DESC, CATEGORIA_NOME ASC");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>

<body class="listar-produtos-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Lista de categorias</h2>
            <div>
                <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
                <a href="cadastrar_categoria.php" class="btn btn-primary">Adicionar categoria</a>
            </div>
        </div>

        <table class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th scope="col" class="text-center">ID</th>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center">Descrição</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($categorias as $categoria): ?>
                    <tr>

                        <td class="text-center">
                            <?php echo $categoria['CATEGORIA_ID']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $categoria['CATEGORIA_NOME']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $categoria['CATEGORIA_DESC']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $categoria['CATEGORIA_ATIVO'] ? 'Ativo' : 'Inativo'; ?>
                        </td>

                        <td class="text-center">
                            <a href="editar_categoria.php?id=<?php echo $categoria['CATEGORIA_ID']; ?>"
                                class="btn btn-primary">
                                <img src="../assets/imgs/icons/edit.svg" alt="">
                            </a>
                            <a href="excluir_categoria.php?id=<?php echo $categoria['CATEGORIA_ID']; ?>"
                                class="btn btn-danger">
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