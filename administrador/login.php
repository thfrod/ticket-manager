<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de login</title>
</head>
<body>
    <h2>Login do Admnistrador</h2>

    <form action="processa_login.php" method="post"> 
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <p> 
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>
        <p>
        <input type="submit" value="entrar">
        
        <?php
        if(isset($_GET['erro'])){
            echo'<p style="color:red;"> Nome de usuario ou senha incorretos</p>';
        }
        // post é para informações confidenciais
        //Required indica que é obrigatório preencher o campo
        ?>
    </form>
</body>
</html>
