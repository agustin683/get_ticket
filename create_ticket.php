<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $department = $_POST['department'];
    $description = $_POST['description'];

    $query = "INSERT INTO tickets (type, department, description) VALUES ('$type', '$department', '$description')";
    if (mysqli_query($conn, $query)) {
        $ticket_id = mysqli_insert_id($conn);
        echo "<div class='alert alert-success text-center'>✅ Su ticket ha sido registrado exitosamente. El número de su ticket es: TICKET-$ticket_id.</div>";
        header("refresh:5;url=create_ticket.html");
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Error al crear el ticket. Por favor, intente de nuevo.</div>";
    }
}
?>