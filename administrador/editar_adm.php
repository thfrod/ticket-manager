<?php
// criamos um form de cadastrar produtos e usuário irá colocar as infos
// action é onde colacamos o nome do arquivo php para onde mandaremos os dados, no noso caso criamos na mesma página
$title = 'Cadastro de administrador';
require_once("../shared/head.php");

session_start();
require_once('../conexao/conexao.php');
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] == false ) { //se não está definido a variável global admin_logado redireciona para tela de login
    header("Location:login.php"); // redireciona para tela de login
    exit(); // impede que as próximas linhas sejam executadas
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM ADMINISTRADOR WHERE ADM_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $adm = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        header('Location: listar_adms.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // se o método usado for POST execute o if
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE ADMINISTRADOR SET ADM_NOME = :nome, ADM_EMAIL = :email, ADM_SENHA = :senha, ADM_ATIVO = :status WHERE ADM_ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
        $stmt->execute();

        header("Location:listar_adms.php?success=Administrador cadastrado com successo");

    } catch (PDOException $e) {
        header("Location:listar_adms.php?error=Erro ao cadastrar administrador");

    }
    ;
}


?>

<body class="cadastrar-produto-content d-flex">
    <?php require("../shared/aside.php") ?>

    <div class="container my-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2>Cadastrar Administrador</h2>
            <a href="painel_admin.php" class="btn btn-primary">Voltar ao Painel</a>
        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $adm['ADM_ID']; ?>">

            <div class="input-group mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome"
                    value="<?php echo $adm['ADM_NOME'] ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="email" name="email" id="email" required placeholder="Email"
                    value="<?php echo $adm['ADM_EMAIL'] ?>">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="password" name="senha" id="senha" required placeholder="Senha"
                    value="<?php echo $adm['ADM_SENHA'] ?>">
            </div>


            <div class="input-group mb-3">
                <select class="form-control" name="status" id="status" required placeholder="Status">
                    <option value="1" <?php echo $adm['ADM_ATIVO'] == 1 ? 'selected' : ''; ?>>
                        Ativa
                    </option>
                    <option value="0" <?php echo $adm['ADM_ATIVO'] == 0 ? 'selected' : ''; ?>>
                        Inativa
                    </option>
                </select>
            </div>

            <input type="submit" value="Cadastrar" class="btn btn-success">
        </form>
    </div>
</body>