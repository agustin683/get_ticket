<?php
session_start();
include('db.php');

// Verificar si el usuario es especialista
if ($_SESSION['role'] != 'specialist') {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];

// Obtener información de los tickets asignados al especialista
$queryAsignados = "SELECT * FROM tickets WHERE assigned_to=(SELECT id FROM users WHERE username='$username')";
$resultAsignados = mysqli_query($conn, $queryAsignados);
$ticketsAsignados = [];
while ($row = mysqli_fetch_assoc($resultAsignados)) {
    $ticketsAsignados[] = $row;
}

// Obtener información de los tickets no asignados
$queryNoAsignados = "SELECT * FROM tickets WHERE assigned_to IS NULL";
$resultNoAsignados = mysqli_query($conn, $queryNoAsignados);
$ticketsNoAsignados = [];
while ($row = mysqli_fetch_assoc($resultNoAsignados)) {
    $ticketsNoAsignados[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Especialista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #add8e6;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Panel de Especialista</h2>
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
        <div class="mt-5">
            <div class="d-flex justify-content-between">
                <h3>Tickets Asignados</h3>
                <input type="text" id="searchAsignados" class="form-control w-25" placeholder="Buscar tickets...">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Ticket</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Progreso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableAsignados">
                        <?php foreach ($ticketsAsignados as $ticket): ?>
                            <tr>
                                <td><?php echo $ticket['id']; ?></td>
                                <td><?php echo $ticket['description']; ?></td>
                                <td><?php echo $ticket['status']; ?></td>
                                <td><?php echo $ticket['progress']; ?></td>
                                <td>
                                    <form class="update-form" data-ticket-id="<?php echo $ticket['id']; ?>">
                                        <select name="status" class="form-control" required>
                                            <option value="pending" <?php echo $ticket['status'] == 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="in_process" <?php echo $ticket['status'] == 'in_process' ? 'selected' : ''; ?>>En Proceso</option>
                                            <option value="resolved" <?php echo $ticket['status'] == 'resolved' ? 'selected' : ''; ?>>Finalizado</option>
                                            <option value="on_hold" <?php echo $ticket['status'] == 'on_hold' ? 'selected' : ''; ?>>En Espera de Información</option>
                                        </select>
                                        <input type="text" name="progress" class="form-control mt-2" placeholder="Progreso (%)" value="<?php echo $ticket['progress']; ?>" required>
                                        <textarea name="comment" class="form-control mt-2" placeholder="Agregar comentario"></textarea>
                                        <button type="submit" class="btn btn-primary btn-block mt-2">Actualizar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <h3>Tickets No Asignados</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Ticket</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Departamento</th>
                            <th>Tipo de Solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableNoAsignados">
                        <?php foreach ($ticketsNoAsignados as $ticket): ?>
                            <tr>
                                <td><?php echo $ticket['id']; ?></td>
                                <td><?php echo $ticket['description']; ?></td>
                                <td><?php echo $ticket['status']; ?></td>
                                <td><?php echo $ticket['department']; ?></td>
                                <td><?php echo $ticket['type']; ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="asignarTicket(<?php echo $ticket['id']; ?>)">Asignar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#searchAsignados").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableAsignados tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $(".update-form").on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var ticketId = form.data("ticket-id");
                $.ajax({
                    url: 'update_ticket.php',
                    type: 'POST',
                    data: form.serialize() + '&ticket_id=' + ticketId,
                    success: function(response) {
                        if (response == 'success') {
                            alert('Ticket actualizado correctamente');
                        } else {
                            alert('Error al actualizar el ticket');
                        }
                    }
                });
            });
        });

        function asignarTicket(ticketId) {
            $.ajax({
                url: 'asignar_ticket.php',
                type: 'POST',
                data: { ticket_id: ticketId },
                success: function(response) {
                    if (response == 'success') {
                        // Mover el ticket a la lista de asignados
                        let row = $("button[onclick='asignarTicket(" + ticketId + ")']").closest('tr');
                        $('#tableAsignados').append(row);
                        row.find('td:last-child').remove(); // Eliminar la columna de acciones
                    } else {
                        alert('Error al asignar el ticket');
                    }
                }
            });
        }
    </script>
</body>
</html>