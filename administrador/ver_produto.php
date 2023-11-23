<?php

$title = 'Ver produto';
require_once("../shared/head.php");

session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

$array = [' https://i.imgur.com/gXpxfvy.jpg', 'https://i.imgur.com/g5ZXHRr.jpg', 'https://i.imgur.com/71z2yaO.jpg'];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM PRODUTO WHERE PRODUTO_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS

    } catch (PDOException $e) {
        header("Location:listar_produtos.php?error=Erro ao buscar produto");
    }
} else {
    header("Location:listar_produtos.php?error=Erro ao buscar produto");
}
?>

<body class="ver-produto-content d-flex">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        <button class="btn btn-primary btn-ver-imagens">
            <span class="material-symbols-outlined text-white">image</span>
            <span>
                Ver imagens
            </span>
        </button>

        <div class="overlay">
            <div id="carousel" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true"
                        aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($array as $img): ?>
                        <div class="carousel-item <?php echo ($img == $array[0] ? 'active' : '') ?>">
                            <img src="<?php echo $img ?>" class="d-block w-100" alt="...">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</body>