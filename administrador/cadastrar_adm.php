<?php
// criamos um form de cadastrar produtos e usuário irá colocar as infos
// action é onde colacamos o nome do arquivo php para onde mandaremos os dados, no noso caso criamos na mesma página
$title = 'Cadastro de administrador';
require_once("../shared/head.php");

session_start();
require_once('../conexao/conexao.php');
if (!isset($_SESSION['admin_logado'])) { //se não está definido a variável global admin_logado redireciona para tela de login
    header("Location:login.php"); // redireciona para tela de login
    exit(); // impede que as próximas linhas sejam executadas
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // se o método usado for POST execute o if
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $status = $_POST['status'];

    try {
        $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) VALUES(:nome, :email, :senha, :status)";
        $stmt = $pdo->prepare($sql);
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
            <div class="input-group mb-3">
                <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="email" name="email" id="email" required placeholder="Email">
            </div>

            <div class="input-group mb-3">
                <input class="form-control" type="password" name="senha" id="senha" required placeholder="Senha">
            </div>


            <div class="input-group mb-3">
                <select class="form-control" name="status" id="status" required placeholder="Status">
                    <option value="1">Ativa</option>
                    <option value="0">Inativa</option>
                </select>
            </div>

            <input type="submit" value="Cadastrar" class="btn btn-success">
        </form>
    </div>
</body>