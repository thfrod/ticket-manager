<?php

$title = 'Listagem de Administradores';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

try {
    $stmt = $pdo->prepare("SELECT ADM_ID, ADM_NOME, ADM_EMAIL,ADM_ATIVO FROM ADMINISTRADOR ORDER BY ADM_ATIVO DESC");
    $stmt->execute();
    $adms = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>

<body class="listar-produtos-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Lista de Administradores</h2>
            <div>
                <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
                <a href="cadastrar_adm.php" class="btn btn-primary">Adicionar Administrador</a>
            </div>
        </div>

        <table class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th scope="col" class="text-center">Nome</th>
                    <th scope="col" class="text-center">E-mail</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($adms as $adm): ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $adm['ADM_NOME']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $adm['ADM_EMAIL']; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $adm['ADM_ATIVO'] ? 'Ativo' : 'Inativo'; ?>
                        </td>

                        <td class="text-center">
                            <a href="editar_adm.php?id=<?php echo $adm['ADM_ID']; ?>" class="btn btn-primary">
                                <img src="../assets/imgs/icons/edit.svg" alt="">
                            </a>
                            <a href="excluir_adm.php?id=<?php echo $adm['ADM_ID']; ?>" class="btn btn-danger">
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