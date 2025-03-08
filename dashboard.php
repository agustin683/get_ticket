<?php
session_start();
include('db.php');

// Verificar si el usuario es administrador o especialista
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'specialist') {
    header("Location: index.html");
    exit();
}

// Obtener el total de tickets
$query = "SELECT COUNT(*) AS total_tickets FROM tickets";
$result = mysqli_query($conn, $query);
$total_tickets = mysqli_fetch_assoc($result)['total_tickets'];

// Obtener tickets pendientes
$query = "SELECT COUNT(*) AS pending_tickets FROM tickets WHERE status='pending'";
$result = mysqli_query($conn, $query);
$pending_tickets = mysqli_fetch_assoc($result)['pending_tickets'];

// Obtener tickets en proceso
$query = "SELECT COUNT(*) AS in_process_tickets FROM tickets WHERE status='in_process'";
$result = mysqli_query($conn, $query);
$in_process_tickets = mysqli_fetch_assoc($result)['in_process_tickets'];

// Obtener tickets resueltos
$query = "SELECT COUNT(*) AS resolved_tickets FROM tickets WHERE status='resolved'";
$result = mysqli_query($conn, $query);
$resolved_tickets = mysqli_fetch_assoc($result)['resolved_tickets'];

// Obtener tickets cancelados
$query = "SELECT COUNT(*) AS cancelled_tickets FROM tickets WHERE status='cancelled'";
result = mysqli_query($conn, $query);
$cancelled_tickets = mysqli_fetch_assoc($result)['cancelled_tickets'];

// Obtener tickets en pausa
$query = "SELECT COUNT(*) AS on_hold_tickets FROM tickets WHERE status='on_hold'";
$result = mysqli_query($conn, $query);
$on_hold_tickets = mysqli_fetch_assoc($result)['on_hold_tickets'];

// Obtener las tareas más solicitadas
$query = "SELECT type, COUNT(*) AS count FROM tickets GROUP BY type";
$result = mysqli_query($conn, $query);
$tickets_by_type = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tickets_by_type[] = $row;
}

// Obtener las solicitudes por departamento
$query = "SELECT department, COUNT(*) AS count FROM tickets GROUP BY department";
$result = mysqli_query($conn, $query);
$tickets_by_department = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tickets_by_department[] = $row;
}

// Obtener información de los especialistas asignados y el estado de los tickets
$query = "SELECT t.id, t.description, t.status, u.username AS specialist, t.progress FROM tickets t LEFT JOIN users u ON t.assigned_to = u.id";
$result = mysqli_query($conn, $query);
$tickets_info = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tickets_info[] = $row;
}

echo json_encode([
    'total_tickets' => $total_tickets,
    'pending_tickets' => $pending_tickets,
    'in_process_tickets' => $in_process_tickets,
    'resolved_tickets' => $resolved_tickets,
    'cancelled_tickets' => $cancelled_tickets,
    'on_hold_tickets' => $on_hold_tickets,
    'tickets_by_type' => $tickets_by_type,
    'tickets_by_department' => $tickets_by_department,
    'tickets_info' => $tickets_info
]);
?>