<?php
include 'db.php';

// Adicionar produto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (name, price, description, stock) VALUES ('$name', '$price', '$description', '$stock')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo produto adicionado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Obter todos os produtos
$products = $conn->query("SELECT id, name, price, description, stock FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
</head>
<body>
    <h1>Adicionar Produto</h1>
    <form method="post" action="products.php">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="price">Preço:</label>
        <input type="text" id="price" name="price" required>
        <br>
        <label for="description">Descrição:</label>
        <textarea id="description" name="description"></textarea>
        <br><br>
        <button type="submit">Adicionar</button>
        <a style="background: beige;
    color: black;
    padding: 0.2rem 2rem;" href="./index.php">Volta</a>
    </form>
    </form>

    <h1>Produtos Existentes</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Descrição</th>
        </tr>
        <?php while($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['stock']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
