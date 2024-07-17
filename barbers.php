<?php
include 'db.php';

// Adicionar barbeiro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO barbers (name, email, phone) VALUES ('$name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo barbeiro adicionado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Obter todos os barbeiros
$barbers = $conn->query("SELECT id, name, email, phone FROM barbers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Barbeiros</title>
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
                <form method="post" action="barbers.php">
                    <h1>Adicionar <strong class="Logo">Barbeiro</strong></h1>
                    <input placeholder="Nome Completo" type="text" id="name" name="name" required>
                    <input placeholder="Email Profissional" type="email" id="email" name="email" required>
                    <input placeholder="(00) 0 0000-0000" type="text" id="phone" name="phone">
                    <button type="submit">Adicionar</button>
                </form>
            </article>
            <!--<h1>Barbeiros Existentes</h1>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                </tr>
                <?php while($row = $barbers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>-->
        </section>
    </main>
</body>
</html>
