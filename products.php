<?php
include 'db.php';

// Adicionar produto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (name, price) VALUES ('$name', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Bom trabalho!',
                text: 'Produto adicionado com sucesso!',
                icon: 'success',
            });
        });
        </script>";
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erro!',
                text: 'Erro: " . $conn->error . "',
                icon: 'error',
            });
        });
        </script>";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--css-->
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/barbers.css">
    <!--css-->
</head>
<body>
    <main>
        <header>
            <!--Logo-->
            <strong>
                <a href="./index.php" class="Logo">ARBarber</a>
            </strong>
            <!--Logo-->
            <!--Paginas-->
            <nav>
                <ul>
                    <li><a href="barbers.php">Barbeiros</a></li>
                    <li><a href="clients.php">Clientes</a></li>
                    <li><a href="appointments.php">Agendamentos</a></li>
                    <li><a href="products.php">Produtos</a></li>
                </ul>
            </nav>
            <!--Paginas-->
        </header>
        <section>
            <article>    
                <form method="post" action="products.php">
                    <h1>Adicionar <strong class="Logo">Produto</strong></h1>
                    <input placeholder="Nome do Produto" type="text" id="name" name="name" required>
                    <input placeholder="Preço do Produto" type="text" id="price" name="price" required>
                    <button type="submit">Adicionar</button>
                </form>
            </article>
        </section>
    </main>            
</body>
</html>
