<?php
session_start();
include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $specialist_id = $_SESSION['user_id'];

    // Actualizar el ticket para asignarlo al especialista
    $query = "UPDATE tickets SET assigned_to = ?, status = 'in_process' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $specialist_id, $ticket_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>