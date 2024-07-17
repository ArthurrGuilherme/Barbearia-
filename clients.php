<?php
include 'db.php';

// Adicionar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO clients (name, email, phone) VALUES ('$name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Bom trabalho!',
                text: 'Novo cliente adicionado com sucesso!',
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

// Obter todos os clientes
$clients = $conn->query("SELECT id, name, email, phone FROM clients");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Clientes</title>
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
            <form method="post" action="clients.php">
                <h1>Adicionar <strong class="Logo">Cliente</strong></h1>
                <input placeholder="Nome Completo" type="text" id="name" name="name" required>
                <input placeholder="Email" type="email" id="email" name="email" required>
                <input placeholder="(00) 0 0000-0000" type="text" id="phone" name="phone">
                <button type="submit">Adicionar</button>
            </form>
        </article>
    </section>
</body>
</html>
