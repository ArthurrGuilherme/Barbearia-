<?php
include 'db.php';

// Adicionar agendamento e produtos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barber_id = $_POST['barber_id'];
    $client_id = $_POST['client_id'];
    $appointment_date = $_POST['appointment_date'];
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];

    $sql = "INSERT INTO appointments (barber_id, client_id, appointment_date) VALUES ('$barber_id', '$client_id', '$appointment_date')";

    if ($conn->query($sql) === TRUE) {
        $appointment_id = $conn->insert_id;

        // Adicionar produtos ao agendamento
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $quantity = $quantities[$i];
            $sql = "INSERT INTO appointment_products (appointment_id, product_id, quantity) VALUES ('$appointment_id', '$product_id', '$quantity')";
            $conn->query($sql);
        }

        echo "Novo agendamento criado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Obter barbeiros, clientes e produtos para os dropdowns
$barbers = $conn->query("SELECT id, name FROM barbers");
$clients = $conn->query("SELECT id, name FROM clients");
$products = $conn->query("SELECT id, name, price FROM products");

// Obter todos os agendamentos e produtos associados
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
");

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

while ($row = $result->fetch_assoc()) {
    $appointment_products[$row['appointment_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Agendamentos</title>
</head>
<body>
    <h1>Criar Agendamento</h1>
    <form method="post" action="appointments.php">
        <label for="barber_id">Barbeiro:</label>
        <select id="barber_id" name="barber_id" required>
            <?php while($row = $barbers->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label for="client_id">Cliente:</label>
        <select id="client_id" name="client_id" required>
            <?php while($row = $clients->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label for="appointment_date">Data e Hora:</label>
        <input type="datetime-local" id="appointment_date" name="appointment_date" required>
        <br>
        <label for="product_id[]">Produtos:</label>
        <select id="product_id[]" name="product_id[]" multiple>
            <?php while($row = $products->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> - R$<?php echo $row['price']; ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label for="quantity[]">Quantidade:</label>
        <input type="number" id="quantity[]" name="quantity[]" min="1" value="1">
        <br>
        <button type="submit">Criar</button>
    </form>

    <h1>Agendamentos Existentes</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Barbeiro</th>
            <th>Cliente</th>
            <th>Data e Hora</th>
            <th>Status</th>
            <th>Produtos</th>
        </tr>
        <?php while($row = $appointments->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['barber_name']; ?></td>
                <td><?php echo $row['client_name']; ?></td>
                <td><?php echo $row['appointment_date']; ?></td>
                <td><?php echo $row['status']; ?></td>
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
</body>
</html>
