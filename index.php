<?php
include 'db.php';

// Obter barbeiros, clientes e produtos para os dropdowns
$barbers = $conn->query("SELECT id, name FROM barbers");
$clients = $conn->query("SELECT id, name FROM clients");
$products = $conn->query("SELECT id, name, price FROM products");

// Obter a data atual no formato adequado para consulta SQL
$today = date('Y-m-d');

// Obter todos os agendamentos do dia atual
$appointments = $conn->query("
    SELECT 
        appointments.id, 
        barbers.name AS barber_name, 
        clients.name AS client_name, 
        appointments.appointment_date, 
        appointments.status 
    FROM 
        appointments 
    JOIN 
        barbers ON appointments.barber_id = barbers.id 
    JOIN 
        clients ON appointments.client_id = clients.id
    WHERE 
        DATE(appointments.appointment_date) = '$today'
");

if (!$appointments) {
    die("Erro na consulta de agendamentos: " . $conn->error);
}

$appointment_products = [];
$result = $conn->query("
    SELECT 
        appointment_products.appointment_id, 
        products.name, 
        appointment_products.quantity 
    FROM 
        appointment_products 
    JOIN 
        products ON appointment_products.product_id = products.id
");

if (!$result) {
    die("Erro na consulta de produtos: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $appointment_products[$row['appointment_id']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARBARBER Sistema de Agendamentos</title>
    <!--css-->
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/indexx.css">
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
            <!--Texto de apresentação-->
            <article>
                <h1>Bem-vindo ao sistema de agendamento da <strong class="Logo">ARBarber</strong></h1>
            </article>
            <!--Texto de apresentação-->   
            <br>
            <div class="Tabela"> 
                <table border="1">
                    <tr>
                        <th>Barbeiro</th>
                        <th>Cliente</th>
                        <th>Data e Hora</th>
                        <th>Produtos</th>
                    </tr>
                    <?php while($row = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['barber_name']; ?></td>
                            <td><?php echo $row['client_name']; ?></td>
                            <td><?php echo $row['appointment_date']; ?></td>
                            <td>
                                <ul>
                                    <?php if (isset($appointment_products[$row['id']])): ?>
                                        <?php foreach ($appointment_products[$row['id']] as $product): ?>
                                            <li><?php echo $product['name']; ?> (x<?php echo $product['quantity']; ?>)</li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>Sem produtos</li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </section>      
    </main>    
</body>
</html>
