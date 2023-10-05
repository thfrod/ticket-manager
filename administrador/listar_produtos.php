<?php
session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

require_once('../conexao/conexao.php');

try {
    $stmt = $pdo->prepare("SELECT * FROM PRODUTOS");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); //Recupera todos os registros retornados pela consulta SQL e os armazena na variável $produtos como um array associativo, onde as chaves do array são os nomes das colunas da tabela PRODUTOS
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Listagem de Produtos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            /* Quando o usuário passa o mouse sobre uma linha da tabela (<tr>), o fundo (background-color) dessa linha muda para #f5f5f5, que é um cinza claro.  */
            background-color: #f5f5f5;
        }
    </style>

    <script>
        function confirmarExclusao() {
            return confirm('Tem certeza que deseja excluir este produto?');
        }
    </script>
</head>

<body>
    <h2>Lista de Produtos</h2>
    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($produtos as $produto): ?>
                <tr>

                    <td>
                        <?php echo $produto['id']; ?>
                    </td>
                    <td>
                        <?php echo $produto['nome']; ?>
                    </td>
                    <td>
                        <?php echo $produto['descricao']; ?>
                    </td>
                    <td>
                        <?php echo $produto['preco']; ?>
                    </td>
                    <td><img src="<?php echo $produto['imagem']; ?>" alt="Imagem do Produto" width="50"></td>
                    <td>
                        <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a>
                        <a href="excluir_produto.php?id=<?php echo $produto['id']; ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="painel_admin.php">Voltar ao Painel</a>
</body>

</html>