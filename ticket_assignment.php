<?php
session_start();
include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener lista de especialistas
$specialists = [];
$specialistQuery = "SELECT id, username FROM users WHERE role = 'specialist'";
$specialistResult = $conn->query($specialistQuery);
if ($specialistResult->num_rows > 0) {
    while ($row = $specialistResult->fetch_assoc()) {
        $specialists[] = $row;
    }
}

// Asignar ticket
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_id = $_POST['ticket_id'];
    $specialist_id = $_POST['specialist_id'];

    $assignQuery = "UPDATE tickets SET specialist_id = ?, status = 'En proceso' WHERE id = ?";
    $stmt = $conn->prepare($assignQuery);
    $stmt->bind_param("ii", $specialist_id, $ticket_id);
    if ($stmt->execute()) {
        $message = "Ticket asignado correctamente.";
    } else {
        $message = "Error al asignar el ticket.";
    }
    $stmt->close();
}

// Obtener lista de tickets no asignados
$tickets = [];
$ticketQuery = "SELECT id, description FROM tickets WHERE specialist_id IS NULL";
$ticketResult = $conn->query($ticketQuery);
if ($ticketResult->num_rows > 0) {
    while ($row = $ticketResult->fetch_assoc()) {
        $tickets[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Tickets - Ticket-G32</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .content {
            flex: 1;
            display: flex;
        }
        .sidebar {
            min-width: 250px;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar .nav-link {
            padding: 15px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Bienvenido, Administrador</div>
        <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
    </div>
    <div class="content">
        <nav class="sidebar">
            <div class="p-3">
                <h4>Menú</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="ticket_assignment.php"><i class="fas fa-folder"></i> Asignación de Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ticket_tracking.php"><i class="fas fa-user-secret"></i> Seguimiento de Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-door-open"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="main-content">
            <h1>Asignación de Tickets</h1>
            <?php if (isset($message)): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    Tickets No Asignados
                </div>
                <div class="card-body">
                    <form method="POST" action="ticket_assignment.php">
                        <div class="mb-3">
                            <label for="ticket_id" class="form-label">Seleccionar Ticket</label>
                            <select class="form-select" id="ticket_id" name="ticket_id" required>
                                <?php foreach ($tickets as $ticket): ?>
                                    <option value="<?php echo $ticket['id']; ?>"><?php echo $ticket['description']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="specialist_id" class="form-label">Asignar a Especialista</label>
                            <select class="form-select" id="specialist_id" name="specialist_id" required>
                                <?php foreach ($specialists as $specialist): ?>
                                    <option value="<?php echo $specialist['id']; ?>"><?php echo $specialist['username']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Asignar Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>