<?php
include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $status = $_POST['status'];
    $progress = $_POST['progress'];
    $comment = $_POST['comment'];

    // Actualizar el ticket con la nueva información
    $query = "UPDATE tickets SET status = ?, progress = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $status, $progress, $ticket_id);
    if ($stmt->execute()) {
        // Insertar el comentario en la tabla de comentarios
        if (!empty($comment)) {
            $insertComment = "INSERT INTO comments (ticket_id, comment) VALUES (?, ?)";
            $stmtComment = $conn->prepare($insertComment);
            $stmtComment->bind_param('is', $ticket_id, $comment);
            $stmtComment->execute();
            $stmtComment->close();
        }
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->close();
}
?>