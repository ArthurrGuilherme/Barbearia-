<?php
include 'db.php';

// Verificar se houve envio de formulário via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se todas as variáveis esperadas estão definidas
    if (
        isset($_POST['barber_id']) &&
        isset($_POST['client_id']) &&
        isset($_POST['appointment_date']) &&
        isset($_POST['product_id']) &&
        isset($_POST['quantity'])
    ) {
        $barber_id = $_POST['barber_id'];
        $client_id = $_POST['client_id'];
        $appointment_date = $_POST['appointment_date'];
        $product_ids = $_POST['product_id'];
        $quantities = $_POST['quantity'];

        // Verificar se $product_ids e $quantities são arrays e têm o mesmo tamanho
        if (is_array($product_ids) && is_array($quantities) && count($product_ids) === count($quantities)) {
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

                // Exibir SweetAlert de sucesso
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Bom trabalho!',
                            text: 'Novo agendamento criado com sucesso!',
                            icon: 'success',
                        });
                    });
                </script>";
            } else {
                // Exibir SweetAlert de erro
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Erro!',
                            text: 'Erro ao criar agendamento: " . $conn->error . "',
                            icon: 'error',
                        });
                    });
                </script>";
            }
        } else {
            // Exibir SweetAlert de erro para produtos e quantidades
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Produtos e quantidades devem ser arrays e ter o mesmo tamanho.',
                        icon: 'error',
                    });
                });
            </script>";
        }
    } else {
        // Exibir SweetAlert de erro para campos obrigatórios
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Todos os campos são obrigatórios.',
                    icon: 'error',
                });
            });
        </script>";
    }
}

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
    <!--css-->
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/barbers.css">
    <!--css-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <form method="post" action="appointments.php">
                <h1>Criar Agendamento</h1>
                <div>
                    <label for="barber_id">Barbeiro:</label>
                    <select id="barber_id" name="barber_id" required>
                        <?php while($row = $barbers->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="client_id">Cliente:</label>
                    <select id="client_id" name="client_id" required>
                        <?php
                        // Reiniciar a consulta para os clientes, pois o ponteiro do resultado já foi percorrido
                        $clients->data_seek(0);
                        while($row = $clients->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div> 
                <div>
                    <label for="appointment_date">Data e Hora:</label>
                    <input type="datetime-local" id="appointment_date" name="appointment_date" required>
                </div>
                <div>
                    <label for="product_id[]">Produtos:</label>
                    <select id="product_id[]" name="product_id[]" multiple>
                        <?php while($row = $products->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> - R$<?php echo $row['price']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Criar</button>
            </form>
        </article>
    </section>
</main>
</body>
</html>
