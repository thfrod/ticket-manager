<?php

$title = 'Edição de Produto';
require_once("../shared/head.php");

//uma sessão é iniciada e verifica-se se um administrador está logado. Se não estiver, ele é redirecionado para a página de login.
session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

//o script faz uma conexão com o banco de dados, usando os detalhes de configuração especificados em conexao.php
require_once('../conexao/conexao.php');

// Se a página foi acessada via método GET, o script tenta recuperar os detalhes do produto com base no ID passado na URL.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM PRODUTO WHERE PRODUTO_ID = :id"); //Quando você executa uma consulta SELECT no banco de dados usando PDO e utiliza o método fetch(PDO::FETCH_ASSOC), o resultado é um array associativo, onde cada chave do array é o nome de uma coluna da tabela no banco de dados, e o valor associado a essa chave é o valor correspondente daquela coluna para o registro selecionado
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC); //$produto é um array associativo que contém os detalhes do produto que foi recuperado do banco de dados. Por exemplo, se a tabela de produtos tem colunas como ID, NOME, DESCRICAO, PRECO, e URL_IMAGEM, então o array $produto terá essas chaves, e você pode acessar os valores correspondentes usando a sintaxe de colchetes, 

            $stmtCategoria = $pdo->prepare("SELECT CATEGORIA_NOME, CATEGORIA_ID FROM CATEGORIA WHERE CATEGORIA_ATIVO = 1");
            $stmtCategoria->execute();
            $categorias = $stmtCategoria->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        header('Location: listar_produtos.php');
        exit();
    }
}

// Se o formulário de edição foi submetido, a página é acessada via método POST, e o script tenta atualizar os detalhes do produto no banco de dados com as informações fornecidas no formulário.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['PRODUTO_ID'];
    $nome = $_POST['PRODUTO_NOME'];
    $descricao = $_POST['PRODUTO_DESC'];
    $preco = $_POST['PRODUTO_PRECO'];
    $url_imagem = $_POST['url_imagem'];

    try {
        $stmt = $pdo->prepare("UPDATE PRODUTOS SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descricao, PRODUTO_PRECO = :preco, URL_IMAGEM = :url_imagem WHERE ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':url_imagem', $url_imagem, PDO::PARAM_STR);
        $stmt->execute();

        header("Location:listar_produtos.php?success=Produto atualizado com successo");

        exit();

    } catch (PDOException $e) {
        header("Location:listar_produtos.php?error=Erro ao atualizar produto");
    }
}
?>

<body class="editar-produto-content d-flex">
    <?php require("../shared/aside.php") ?>
    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Editar Produto</h2>
            <a href="listar_produtos.php" class="btn btn-primary">Voltar à Lista de Produtos</a>
        </div>


        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $produto['PRODUTO_ID']; ?>">

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome"
                    value="<?php echo $produto['PRODUTO_NOME']; ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="descricao" id="descricao" required placeholder="Descrição"
                    value="<?php echo $produto['PRODUTO_DESC']; ?>">
            </div>

            <div class="input-group mb-3">
                <select class="form-control" name="status" id="status" required placeholder="Status">
                    <option value="1" <?php echo $produto['PRODUTO_ATIVO'] == 1 ? 'selected' : ''; ?>>
                        Ativa
                    </option>
                    <option value="0" <?php echo $produto['PRODUTO_ATIVO'] == 0 ? 'selected' : ''; ?>>
                        Inativa
                    </option>
                </select>
            </div>

            <div class="input-group mb-3">
                <select class="form-control" name="categoria" id="categoria" required>
                    <option value="0" selected disabled>Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['CATEGORIA_ID'] ?>" <?php echo $produto['CATEGORIA_ID'] == $categoria['CATEGORIA_ID'] ? 'selected' : ''; ?>>
                            <?php echo $categoria['CATEGORIA_NOME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="preco" id="preco" step="0.01" required
                    placeholder="Preço" value="<?php echo $produto['PRODUTO_PRECO']; ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="desconto" id="desconto" step="0.01" required
                    placeholder="Desconto" value="<?php echo $produto['PRODUTO_DESCONTO']; ?>">
            </div>


            AQUI DEVE VIR A LISTA DE IMAGENS RELACIONADAS AQUELE PRODUTO POREM AINDA NÂO FIZ O SELECT JOIN NO BANCO DE
            DADOS
            <!-- <div class="input-group mb-3">
                <input class="form-control" type="text" name="imagem" id="imagem" required placeholder="Imagem" value="<?php echo $produto['PRODUTO_DESC']; ?>">
            </div> -->

            <input type="submit" value="Cadastrar" class="btn btn-success">

        </form>
    </div>
</body>