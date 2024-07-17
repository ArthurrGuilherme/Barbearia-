<?php
include 'db.php';

// Adicionar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO clients (name, email, phone) VALUES ('$name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo cliente adicionado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
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
</head>
<body>
    <h1>Adicionar Cliente</h1>
    <form method="post" action="clients.php">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="phone">Telefone:</label>
        <input type="text" id="phone" name="phone">
        <br><br>
        <button type="submit">Adicionar</button>
        <a style="background: beige;
    color: black;
    padding: 0.2rem 2rem;" href="./index.php">Volta</a>
    </form>

    <h1>Clientes Existentes</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
        </tr>
        <?php while($row = $clients->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
