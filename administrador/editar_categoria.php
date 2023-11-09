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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM CATEGORIA WHERE CATEGORIA_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        header('Location: listar_categorias.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // se o método usado for POST execute o if
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    try {
        $sql = "INSERT INTO CATEGORIA (CATEGORIA_NOME, CATEGORIA_DESC, CATEGORIA_ATIVO) VALUES(:nome, :descricao, :status)";
        $stmt = $pdo->prepare($sql); // (prepare) é um método do pdo que impede qualquer ação que um invasor tente fazer
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); // verifica se a variavel nome que foi escrita atende os parâmetros do PDO
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
        $stmt->execute();

        echo "<p style='color:green;> Categoria cadastro com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar categoria" . $e->getMessage() . "</p>";
    }
    ;
}


?>

<body class="cadastrar-produto-content d-flex">
    <?php require("../shared/aside.php") ?>

    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Editar categoria</h2>
            <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome"
                    value="<?php echo $categoria['CATEGORIA_NOME']; ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="descricao" id="descricao" required
                    value="<?php echo $categoria['CATEGORIA_DESC']; ?>" placeholder="Descrição">
            </div>

            <div class="input-group mb-3">
                <select class="form-control" name="status" id="status" required placeholder="Status">
                    <option value="1" <?php echo $categoria['CATEGORIA_ATIVO'] == 1 ? 'selected' : ''; ?>>
                        Ativa
                    </option>
                    <option value="0" <?php echo $categoria['CATEGORIA_ATIVO'] == 0 ? 'selected' : ''; ?>>
                        Inativa
                    </option>
                </select>
            </div>

            <input type="submit" value="Cadastrar" class="btn btn-success">

        </form>
    </div>
</body>