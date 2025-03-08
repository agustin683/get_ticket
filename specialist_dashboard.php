<?php
session_start();
include('db.php');

// Verificar si el usuario es especialista
if ($_SESSION['role'] != 'specialist') {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];

// Obtener información inicial de los tickets asignados al especialista
$queryAsignados = "SELECT * FROM tickets WHERE assigned_to=(SELECT id FROM users WHERE username='$username') AND status != 'resolved'";
$resultAsignados = mysqli_query($conn, $queryAsignados);
$ticketsAsignados = [];
while ($row = mysqli_fetch_assoc($resultAsignados)) {
    $ticketsAsignados[] = $row;
}

// Obtener información inicial de los tickets resueltos por el especialista
$queryResueltos = "SELECT * FROM tickets WHERE assigned_to=(SELECT id FROM users WHERE username='$username') AND status = 'resolved'";
$resultResueltos = mysqli_query($conn, $queryResueltos);
$ticketsResueltos = [];
while ($row = mysqli_fetch_assoc($resultResueltos)) {
    $ticketsResueltos[] = $row;
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
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f8f9fa;
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
        .table-responsive {
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Bienvenido, <?php echo $_SESSION['username']; ?></div>
        <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
    </div>
    <div class="content">
        <nav class="sidebar">
            <div class="p-3">
                <h4>Menú</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="linkAsignados"><i class="fas fa-tasks"></i> Tickets Asignados a Mí</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="linkResueltos"><i class="fas fa-check"></i> Tickets Resueltos</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="main-content">
            <div id="asignados" class="table-responsive">
                <h1>Tickets Asignados a Mí</h1>
                <input type="text" id="searchAsignados" class="form-control mb-3" placeholder="Buscar tickets...">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número de Ticket</th>
                            <th>Fecha de Creación</th>
                            <th>Estado Actual</th>
                            <th>Departamento</th>
                            <th>Tipo de Solicitud</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableAsignados">
                        <?php foreach ($ticketsAsignados as $ticket): ?>
                            <tr data-ticket-id="<?php echo $ticket['id']; ?>">
                                <td><?php echo $ticket['id']; ?></td>
                                <td><?php echo $ticket['created_at']; ?></td>
                                <td><?php echo $ticket['status']; ?></td>
                                <td><?php echo $ticket['department']; ?></td>
                                <td><?php echo $ticket['type']; ?></td>
                                <td><?php echo $ticket['description']; ?></td>
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
            <div id="resueltos" class="table-responsive hidden">
                <h1>Tickets Resueltos</h1>
                <input type="text" id="searchResueltos" class="form-control mb-3" placeholder="Buscar tickets...">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número de Ticket</th>
                            <th>Fecha de Creación</th>
                            <th>Estado Actual</th>
                            <th>Departamento</th>
                            <th>Tipo de Solicitud</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableResueltos">
                        <?php foreach ($ticketsResueltos as $ticket): ?>
                            <tr data-ticket-id="<?php echo $ticket['id']; ?>">
                                <td><?php echo $ticket['id']; ?></td>
                                <td><?php echo $ticket['created_at']; ?></td>
                                <td><?php echo $ticket['status']; ?></td>
                                <td><?php echo $ticket['department']; ?></td>
                                <td><?php echo $ticket['type']; ?></td>
                                <td><?php echo $ticket['description']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#linkAsignados').click(function(){
                $('#resueltos').addClass('hidden');
                $('#asignados').removeClass('hidden');
            });
            $('#linkResueltos').click(function(){
                $('#asignados').addClass('hidden');
                $('#resueltos').removeClass('hidden');
            });

            $("#searchAsignados").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableAsignados tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $("#searchResueltos").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableResueltos tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
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
                            if (form.find('select[name="status"]').val() == 'resolved') {
                                var row = form.closest('tr');
                                $('#tableResueltos').append(row);
                                row.find('td:last-child').remove(); // Eliminar la columna de acciones
                            }
                        } else {
                            alert('Error al actualizar el ticket');
                        }
                    }
                });
            });

            function loadTickets() {
                $.ajax({
                    url: 'get_tickets.php',
                    type: 'GET',
                    success: function(data) {
                        var response = JSON.parse(data);
                        $('#tableAsignados').html(response.asignados);
                        $('#tableResueltos').html(response.resueltos);
                    }
                });
            }

            setInterval(loadTickets, 5000); // Recargar tickets cada 5 segundos
        });
    </script>
</body>
</html>