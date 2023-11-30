<?php
// criamos um form de cadastrar produtos e usuário irá colocar as infos
// action é onde colacamos o nome do arquivo php para onde mandaremos os dados, no noso caso criamos na mesma página
$title = 'Cadastro de Produto';
require_once("../shared/head.php");

session_start();
require_once('../conexao/conexao.php');
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false) { //se não está definido a variável global admin_logado redireciona para tela de login
    header("Location:login.php"); // redireciona para tela de login
    exit(); // impede que as próximas linhas sejam executadas
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT CATEGORIA_NOME, CATEGORIA_ID FROM CATEGORIA WHERE CATEGORIA_ATIVO = 1 ORDER BY CATEGORIA_NOME ASC");
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // se o método usado for POST execute o if
    $nome = $_POST['nome']; // pega o dado do name  = "nome" no input
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $ativo = $_POST['ativo'];
    $desconto = $_POST['desconto'];
    $estoque = $_POST['estoque'];
    $categoria = $_POST['categoria'];
    $imagens = $_POST['imagem_url'];

    try {
        $sql = "INSERT INTO PRODUTO (PRODUTO_NOME, PRODUTO_DESC, PRODUTO_PRECO, PRODUTO_DESCONTO, CATEGORIA_ID, PRODUTO_ATIVO) VALUES(:nome, :descricao, :preco, :desconto, :categoria, :ativo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_BOOL);
        $stmt->execute();

        $produto_id = $pdo->lastInsertId();

        $sql_estoque = "INSERT INTO PRODUTO_ESTOQUE (PRODUTO_ID, PRODUTO_QTD) VALUES (:produto_id, :produto_qtd)";
        $stmt_estoque = $pdo->prepare($sql_estoque);
        $stmt_estoque->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
        $stmt_estoque->bindParam(':produto_qtd', $estoque, PDO::PARAM_INT);
        $stmt_estoque->execute();

        foreach ($imagens as $ordem => $url_imagem) {
            $sql_imagem = "INSERT INTO PRODUTO_IMAGEM (IMAGEM_URL, PRODUTO_ID, IMAGEM_ORDEM) VALUES (:url_imagem, :produto_id, :ordem_imagem)";
            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':url_imagem', $url_imagem, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':ordem_imagem', $ordem, PDO::PARAM_INT);
            $stmt_imagem->execute();
        }
        header("Location:listar_produtos.php?success=Produto cadastrado com successo");
    } catch (PDOException $e) {
        header("Location:listar_produtos.php?error=Erro ao cadastrar produto");
    };
}


?>

<body class="cadastrar-produto-content d-flex">
    <?php require("../shared/aside.php") ?>

    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Cadastrar Produto</h2>
            <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="descricao" id="descricao" required placeholder="Descrição">
            </div>

            <div class="input-group mb-3">
                <select class="form-control" name="ativo" id="ativo" required>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <div class="input-group mb-3">
                <select class="form-control" name="categoria" id="categoria" required>
                    <option value="0" selected disabled>Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?php echo $categoria['CATEGORIA_ID'] ?>">
                            <?php echo $categoria['CATEGORIA_NOME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="preco" id="preco" step="0.01" required placeholder="Preço">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="desconto" id="desconto" step="0.01" required placeholder="Desconto">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="estoque" id="estoque" step="1" required placeholder="Estoque">
            </div>

            <div class="d-flex">
                <div class="input-group mb-3">
                    <input class="form-control" type="text" name="imagem_url[]" required placeholder="Imagem">
                </div>
                <div class="btn btn-primary rounded-circle p-1 btn-add-img" onclick="adicionarImagem()">
                    <span class="material-symbols-outlined text-white">add</span>
                </div>
            </div>

            <div class="container-imgs"></div>


            <input type="submit" value="Cadastrar" class="btn btn-success">
        </form>
    </div>
    <script>
        // Adiciona um novo campo de imagem URL.
        function adicionarImagem() {
            const containerImagens = document.querySelector('.container-imgs');
            const novoInput = document.createElement('div');
            novoInput.classList.add('input-group', 'mb-3');
            novoInput.innerHTML = `<input class="form-control" type="text" name="imagem_url[]" required placeholder="Imagem">`;
            containerImagens.appendChild(novoInput);
        }
    </script>
</body>