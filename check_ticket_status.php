<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_number = $_POST['ticket_number'];

    $query = "SELECT * FROM tickets WHERE id='$ticket_number'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $ticket = mysqli_fetch_assoc($result);
        $created_at = $ticket['created_at'];
        $status = $ticket['status'];
        $assigned_specialist = $ticket['assigned_specialist'];
    } else {
        $error = "No se encontró el ticket con el número proporcionado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #add8e6;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: white;
        }
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <h2 class="text-center">Consultar Estado del Ticket</h2>
        <form action="check_ticket_status.php" method="post">
            <div class="mb-3">
                <label for="ticket_number" class="form-label">Número de Ticket:</label>
                <input type="text" class="form-control" id="ticket_number" name="ticket_number" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Consultar Estado</button>
        </form>
        <?php if (isset($ticket)): ?>
            <div class="card">
                <div class="card-header">
                    Resumen del Ticket
                </div>
                <div class="card-body">
                    <p><strong>Fecha de creación:</strong> <?php echo $created_at; ?></p>
                    <p><strong>Estado actual:</strong> <?php echo $status; ?></p>
                    <p><strong>Especialista asignado:</strong> <?php echo $assigned_specialist; ?></p>
                </div>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>