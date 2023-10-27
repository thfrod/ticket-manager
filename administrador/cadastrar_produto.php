<?php
// criamos um form de cadastrar produtos e usuário irá colocar as infos
// action é onde colacamos o nome do arquivo php para onde mandaremos os dados, no noso caso criamos na mesma página
$title = 'Cadastro de Produto';
require_once("../shared/head.php");

session_start();
require_once('../conexao/conexao.php');
if (!isset($_SESSION['admin_logado'])) { //se não está definido a variável global admin_logado redireciona para tela de login
    header("Location:login.php"); // redireciona para tela de login
    exit(); // impede que as próximas linhas sejam executadas
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // se o método usado for POST execute o if
    $nome = $_POST['nome']; // pega o dado do name  = "nome" no input
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem = $_FILES['imagem']['name'];

    //diretório onde a imagem será salva

    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($imagem);

    // gerar a URL da imagem

    $base_url = "http://localhost/thfrod/ticket-manager/";
    $url_imagem = $base_url . $target_file;

    // mover o arquivo de imagem carregado para o diretório de destino

    if (
        move_uploaded_file($_FILES['imagem'] // a função move o arquivo que foi carregado, essa função ta pegando o campo de imagem do form
        ['tmp_name'], $target_file)
    ) { // vai pegar a imagem e jogar em um arquivo temporário (tmp_name)  direciona para o $target_file
        echo "Imagem " . basename($imagem) . " foi carregada";
    } else {
        echo "Falha ao carregar imagem";
    }
    try {
        $sql = "INSERT INTO produtos (Nome, Descricao, Preco, Imagem, Url_Imagem) VALUES(:nome, :descricao, :preco, :imagem, :url_imagem)";
        $stmt = $pdo->prepare($sql); // (prepare) é um método do pdo que impede qualquer ação que um invasor tente fazer
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); // verifica se a variavel nome que foi escrita atende os parâmetros do PDO
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':imagem', $target_file, PDO::PARAM_STR);
        $stmt->bindParam(':url_imagem', $url_imagem, PDO::PARAM_STR);
        $stmt->execute();

        echo "<p style='color:green;> Produto cadastro com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar produto" . $e->getMessage() . "</p>";
    }
    ;
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
                <input class="form-control" type="text" name="descricao" id="descricao" required
                    placeholder="Descrição">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="number" name="preco" id="preco" step="0.01" required
                    placeholder="Preço">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="file" name="imagem" id="imagem" required placeholder="Imagem">
            </div>

            <input type="submit" value="Cadastrar" class="btn btn-success">

        </form>
    </div>
</body>