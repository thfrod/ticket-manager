<?php
require_once("../shared/head.php");
?>
<body class="login-content">

    <form action="processa_login.php" method="post" class="container my-3">

        <h2>Login do Administrador</h2>

        <div class="input-group mb-3">
            <input class="form-control" type="text" name="nome" id="nome" required placeholder="Nome">
        </div>

        <div class="input-group mb-3">
            <input type="password" name="senha" id="senha" required class="form-control" placeholder="Senha">
        </div>
        
        <input type="submit" value="Entrar" class="btn btn-primary">

        <?php
        if (isset($_GET['erro'])) {
            echo '<p style="color:red;" class="mt-3"> Nome de usuario ou senha incorretos</p>';
        }
        // post é para informações confidenciais
        //Required indica que é obrigatório preencher o campo
        ?>
    </form>

</body>