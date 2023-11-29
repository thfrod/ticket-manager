<?php

$title = 'Ver produto';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare(" SELECT p.PRODUTO_ID,p.PRODUTO_NOME, p.PRODUTO_DESC, p.PRODUTO_PRECO, p.PRODUTO_DESCONTO, 
                                        p.PRODUTO_ATIVO, pi.IMAGEM_URL, c.CATEGORIA_NOME, pe.PRODUTO_QTD
                                from PRODUTO p 
                                inner join PRODUTO_IMAGEM pi 
                                on p.PRODUTO_ID = pi.PRODUTO_ID 
                                inner join CATEGORIA c 
                                on p.CATEGORIA_ID = c.CATEGORIA_ID 
                                left outer join PRODUTO_ESTOQUE pe 
                                on p.PRODUTO_ID = pe.PRODUTO_ID 
                                WHERE p.PRODUTO_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmtImgs = $pdo->prepare("SELECT IMAGEM_URL FROM PRODUTO_IMAGEM WHERE PRODUTO_ID = :id");
        $stmtImgs->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtImgs->execute();
        $imgs = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        header("Location:listar_produtos.php?error=Erro ao buscar produto");
    }
} else {
    header("Location:listar_produtos.php?error=Erro ao buscar produto");
}
?>

<body class="ver-produto-content d-flex">
    <link rel="stylesheet" href="../node_modules/@glidejs/glide/dist/css/glide.core.min.css">
    <link rel="stylesheet" href="../node_modules/@glidejs/glide/dist/css/glide.theme.min.css">


    <?php require("../shared/aside.php") ?>

    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Produto #
                <?php echo $id; ?>
            </h2>
            <div>
                <a href="listar_produtos.php" class="btn btn-primary">Voltar a listagem de produtos</a>
                <a href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="btn btn-primary">Editar
                    produto</a>
                <a href="excluir_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="btn btn-danger">Excluir
                    produto</a>
            </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $produto['PRODUTO_ID']; ?>">

            <div class="form-floating  mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome"
                    value="<?php echo$produto['PRODUTO_NOME']; ?>" disabled>
                <label for="nome">Nome do produto</label>

            </div>

            <div class="form-floating mb-3">
                <input disabled class="form-control" type="text" name="descricao" id="descricao" required placeholder="Descrição"
                    value="<?php echo $produto['PRODUTO_DESC']; ?>">
                    <label for="descricao">Descrição</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-control" name="status" id="status" required placeholder="Status">
                    <option selected>
                        <?php echo $produto['PRODUTO_ATIVO'] == 1 ? 'Ativo' : 'Inativo' ?>
                    </option>
                </select>
                <label for="status">Status</label>

            </div>

            <div class="form-floating mb-3">
                <select class="form-control" name="categoria" id="categoria" disabled>
                    <option selected disabled>
                        <?php echo $produto['CATEGORIA_NOME'] ?>
                    </option>
                </select>
                <label for="categoria">Categoria</label>

            </div>

            <div class="form-floating mb-3">
                <input disabled class="form-control" type="number" name="preco" id="preco" step="0.01" required
                    placeholder="Preço" value="<?php echo $produto['PRODUTO_PRECO']; ?>">
                <label for="preco">Preço</label>

            </div>

            <div class="form-floating mb-3">
                <input disabled class="form-control" type="number" name="desconto" id="desconto" step="0.01" required
                    placeholder="Desconto" value="<?php echo $produto['PRODUTO_DESCONTO']; ?>">
                <label for="desconto">Desconto</label>
                    
            </div>

            <div class="form-floating mb-3">
                <input disabled class="form-control" type="number" name="estoque" id="estoque" step="0.01" required
                    placeholder="Estoque" value="<?php echo $produto['PRODUTO_QTD'] ? $produto['PRODUTO_QTD'] : 0; ?>">
                <label for="desconto">Estoque</label>
                    
            </div>

        </form>
        <h3>Imagens</h3>
        <div class="glide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <?php foreach ($imgs as $img): ?>
                        <li class="glide__slide">
                            <img src="<?php echo $img['IMAGEM_URL'] ?>" class="" alt="...">
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="glide__arrows" data-glide-el="controls">
                <button class="glide__arrow glide__arrow--left" data-glide-dir="<">
                    <span class="material-symbols-outlined">
                        arrow_back
                    </span>
                </button>
                <button class="glide__arrow glide__arrow--right" data-glide-dir=">">
                    <span class="material-symbols-outlined">
                        arrow_forward
                    </span>
                </button>
            </div>
        </div>
    </div>
    <script src="../node_modules/@glidejs/glide/dist/glide.min.js"></script>

    <script>

        new Glide('.glide', {
            type: "carousel",
            startAt: 0,
            perView: 4,
            gap: 10,
            autoplay: 2000
        }).mount()
    </script>
</body>