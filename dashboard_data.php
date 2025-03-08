<?php
include('db.php');

// Obtener la cantidad de tickets por estado
$status_counts = array();
$statuses = ['pending', 'in_process', 'resolved', 'cancelled', 'on_hold'];
foreach ($statuses as $status) {
    $query = "SELECT COUNT(*) AS count FROM tickets WHERE status='$status'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $status_counts[] = $row['count'];
}

// Obtener la cantidad de tickets por departamento
$department_counts = array();
$departments = ['Contabilidad', 'Ventas', 'Recursos Humanos', 'Logística', 'Tecnología de la Información'];
foreach ($departments as $department) {
    $query = "SELECT COUNT(*) AS count FROM tickets WHERE department='$department'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $department_counts[] = $row['count'];
}

echo json_encode([
    'ticketStatusCounts' => $status_counts,
    'departmentTicketCounts' => $department_counts
]);
?>