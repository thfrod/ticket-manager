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


            $stmtImagens = $pdo->prepare("SELECT * FROM PRODUTO_IMAGEM WHERE PRODUTO_ID = :id");
            $stmtImagens->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtImagens->execute();
            $imagens = $stmtImagens->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS

        } catch (PDOException $e) {
            header("Location:listar_produtos.php?error=Erro ao editar produto");

        }
    } else {
        header("Location:listar_produtos.php?error=Erro ao editar produto");

        exit();
    }
}

// Se o formulário de edição foi submetido, a página é acessada via método POST, e o script tenta atualizar os detalhes do produto no banco de dados com as informações fornecidas no formulário.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $desconto = $_POST['desconto'];
    $ativo = $_POST['status'];
    $categoria = $_POST['categoria'];
    $imagens = $_POST['imagem_url'];
    $imagensId = $_POST['imagem_id'];



    try {
        $stmt = $pdo->prepare("UPDATE PRODUTO SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descricao, PRODUTO_PRECO = :preco, PRODUTO_DESCONTO = :desconto, CATEGORIA_ID = :categoria, PRODUTO_ATIVO = :ativo WHERE PRODUTO_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_BOOL);
        $stmt->execute();

        foreach ($imagens as $ordem => $url_imagem) {
            $sql_imagem = "UPDATE PRODUTO_IMAGEM SET IMAGEM_URL = :url_imagem WHERE PRODUTO_ID = :id AND IMAGEM_ID = :imagem_id";
            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':url_imagem', $url_imagem, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':imagem_id', $imagensId[$ordem], PDO::PARAM_INT);
            $stmt_imagem->execute();
        }

        header("Location:listar_produtos.php?success=Produto atualizado com successo");

        exit();
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        // header("Location:listar_produtos.php?error=Erro ao atualizar produto");
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
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome" value="<?php echo $produto['PRODUTO_NOME']; ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="descricao" id="descricao" required placeholder="Descrição" value="<?php echo $produto['PRODUTO_DESC']; ?>">
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
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?php echo $categoria['CATEGORIA_ID'] ?>" <?php echo $produto['CATEGORIA_ID'] == $categoria['CATEGORIA_ID'] ? 'selected' : ''; ?>>
                            <?php echo $categoria['CATEGORIA_NOME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="preco" id="preco" step="0.01" required placeholder="Preço" value="<?php echo $produto['PRODUTO_PRECO']; ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="desconto" id="desconto" step="0.01" required placeholder="Desconto" value="<?php echo $produto['PRODUTO_DESCONTO']; ?>">
            </div>

            <div class="container-imgs">
                <?php foreach ($imagens as $imagem) : ?>
                    <div class="d-flex">
                        <div class="input-group mb-3">
                            <input type="hidden" name="imagem_id[]" value="<?php echo $imagem['IMAGEM_ID']; ?>">
                            <input class="form-control" type="text" name="imagem_url[]" id="imagem" required placeholder="Imagem" value="<?php echo $imagem['IMAGEM_URL']; ?>">
                        </div>
                        <!-- <div class="btn btn-danger rounded-circle p-1 btn-add-img" onclick="removeImagem()">
                            <span class="material-symbols-outlined text-white">delete</span>
                        </div> -->
                    </div>

                <?php endforeach; ?>

                <!-- <div class="d-flex">
                    <div class="input-group mb-3">
                        <input class="form-control" type="text" name="imagem_url[]" required placeholder="Imagem">
                    </div>
                    <div class="btn btn-primary rounded-circle p-1 btn-add-img" onclick="adicionarImagem()">
                        <span class="material-symbols-outlined text-white">add</span>
                    </div>
                </div> -->
            </div>

            <input type="submit" value="Cadastrar" class="btn btn-success">

        </form>
    </div>
    <script>
        function adicionarImagem() {
            const containerImagens = document.querySelector('.container-imgs');
            const novoInput = document.createElement('div');
            novoInput.classList.add('input-group', 'mb-3');
            novoInput.innerHTML = `<input class="form-control" type="text" name="imagem_url[]" required placeholder="Imagem">`;
            containerImagens.appendChild(novoInput);
        }

        function removeImagem() {
            event.preventDefault();
            event.target.parentNode.parentNode.remove();
        }
    </script>
</body>