<?php
session_start();
include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Consultas para obtener el número de tickets en cada estado
$pendientesQuery = "SELECT COUNT(*) as count FROM tickets WHERE status = 'pending'";
$enProcesoQuery = "SELECT COUNT(*) as count FROM tickets WHERE status = 'En proceso'";
$finalizadosQuery = "SELECT COUNT(*) as count FROM tickets WHERE status = 'resolved'";
$enEsperaQuery = "SELECT COUNT(*) as count FROM tickets WHERE status = 'En espera de información'";

$pendientesResult = $conn->query($pendientesQuery);
$enProcesoResult = $conn->query($enProcesoQuery);
$finalizadosResult = $conn->query($finalizadosQuery);
$enEsperaResult = $conn->query($enEsperaQuery);

if ($pendientesResult && $enProcesoResult && $finalizadosResult && $enEsperaResult) {
    $pendientesCount = $pendientesResult->fetch_assoc()['count'];
    $enProcesoCount = $enProcesoResult->fetch_assoc()['count'];
    $finalizadosCount = $finalizadosResult->fetch_assoc()['count'];
    $enEsperaCount = $enEsperaResult->fetch_assoc()['count'];
} else {
    $pendientesCount = $enProcesoCount = $finalizadosCount = $enEsperaCount = 0;
}

// Consultas para los gráficos
$departamentoQuery = "SELECT department as departamento, COUNT(*) as count FROM tickets GROUP BY department";
$tipoSolicitudQuery = "SELECT type as tipo_solicitud, COUNT(*) as count FROM tickets GROUP BY type";
$estadoQuery = "SELECT status, COUNT(*) as count FROM tickets GROUP BY status";

$departamentoResult = $conn->query($departamentoQuery);
$tipoSolicitudResult = $conn->query($tipoSolicitudQuery);
$estadoResult = $conn->query($estadoQuery);

$departamentoData = [];
$tipoSolicitudData = [];
$estadoData = [];

if ($departamentoResult) {
    while ($row = $departamentoResult->fetch_assoc()) {
        $departamentoData[] = ['label' => $row['departamento'], 'count' => $row['count']];
    }
}

if ($tipoSolicitudResult) {
    while ($row = $tipoSolicitudResult->fetch_assoc()) {
        $tipoSolicitudData[] = ['label' => $row['tipo_solicitud'], 'count' => $row['count']];
    }
}

if ($estadoResult) {
    while ($row = $estadoResult->fetch_assoc()) {
        $estadoData[] = ['label' => $row['status'], 'count' => $row['count']];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Ticket-G32</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
        .card {
            margin-bottom: 10px;
            cursor: pointer;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Bienvenido, Administrador</div>
        <a href="http://localhost/ticket_system/" class="btn btn-danger">Cerrar sesión</a>
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
                        <a class="nav-link" href="http://localhost/ticket_system/check_ticket_status.html"><i class="fas fa-user-secret"></i> Seguimiento de Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php"><i class="fas fa-chart-bar"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/ticket_system/"><i class="fas fa-door-open"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="main-content">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white" data-bs-toggle="modal" data-bs-target="#modalPendientes">
                        <div class="card-body">
                            <h5 class="card-title">🕒 Pendientes</h5>
                            <p class="card-text"><?php echo $pendientesCount; ?> tickets</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white" data-bs-toggle="modal" data-bs-target="#modalEnProceso">
                        <div class="card-body">
                            <h5 class="card-title">🛠️ En Proceso</h5>
                            <p class="card-text"><?php echo $enProcesoCount; ?> tickets</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white" data-bs-toggle="modal" data-bs-target="#modalFinalizados">
                        <div class="card-body">
                            <h5 class="card-title">✅ Finalizados</h5>
                            <p class="card-text"><?php echo $finalizadosCount; ?> tickets</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEnEspera">
                        <div class="card-body">
                            <h5 class="card-title">⏳ En espera de información</h5>
                            <p class="card-text"><?php echo $enEsperaCount; ?> tickets</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Cantidad de Tickets por Departamento
                        </div>
                        <div class="card-body">
                            <div id="ticketsByDepartment"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Asuntos más Solicitados
                        </div>
                        <div class="card-body">
                            <div id="ticketsBySubject"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Cantidad de Tickets por Tipo de Solicitud
                        </div>
                        <div class="card-body">
                            <div id="ticketsByType"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Estatus General de los Tickets
                        </div>
                        <div class="card-body">
                            <div id="ticketStatus"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modals for ticket details -->
            <div class="modal fade" id="modalPendientes" tabindex="-1" aria-labelledby="modalPendientesLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPendientesLabel">Tickets Pendientes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número de Ticket</th>
                                        <th>Fecha de Creación</th>
                                        <th>Especialista Asignado</th>
                                        <th>Estado Actual</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos
                                    $detallePendientesQuery = "SELECT * FROM tickets WHERE status = 'pending'";
                                    $detallePendientesResult = $conn->query($detallePendientesQuery);
                                    while ($row = $detallePendientesResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        echo "<td>" . ($row['especialista_asignado'] ?? 'No asignado') . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEnProceso" tabindex="-1" aria-labelledby="modalEnProcesoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEnProcesoLabel">Tickets en Proceso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número de Ticket</th>
                                        <th>Fecha de Creación</th>
                                        <th>Especialista Asignado</th>
                                        <th>Estado Actual</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos
                                    $detalleEnProcesoQuery = "SELECT * FROM tickets WHERE status = 'En proceso'";
                                    $detalleEnProcesoResult = $conn->query($detalleEnProcesoQuery);
                                    while ($row = $detalleEnProcesoResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        echo "<td>" . ($row['especialista_asignado'] ?? 'No asignado') . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalFinalizados" tabindex="-1" aria-labelledby="modalFinalizadosLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalFinalizadosLabel">Tickets Finalizados</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número de Ticket</th>
                                        <th>Fecha de Creación</th>
                                        <th>Especialista Asignado</th>
                                        <th>Estado Actual</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos
                                    $detalleFinalizadosQuery = "SELECT * FROM tickets WHERE status = 'resolved'";
                                    $detalleFinalizadosResult = $conn->query($detalleFinalizadosQuery);
                                    while ($row = $detalleFinalizadosResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        echo "<td>" . ($row['especialista_asignado'] ?? 'No asignado') . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEnEspera" tabindex="-1" aria-labelledby="modalEnEsperaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEnEsperaLabel">Tickets en Espera de Información</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número de Ticket</th>
                                        <th>Fecha de Creación</th>
                                        <th>Especialista Asignado</th>
                                        <th>Estado Actual</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('db.php'); // Asegúrate de que este archivo exista y se conecte correctamente a la base de datos
                                    $detalleEnEsperaQuery = "SELECT * FROM tickets WHERE status = 'En espera de información'";
                                    $detalleEnEsperaResult = $conn->query($detalleEnEsperaQuery);
                                    while ($row = $detalleEnEsperaResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        echo "<td>" . ($row['especialista_asignado'] ?? 'No asignado') . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Obtener datos para los gráficos desde PHP
        var departamentoData = <?php echo json_encode($departamentoData); ?>;
        var tipoSolicitudData = <?php echo json_encode($tipoSolicitudData); ?>;
        var estadoData = <?php echo json_encode($estadoData); ?>;

        // Preparar datos para gráficos
        var departamentoLabels = departamentoData.map(function(item) { return item.label; });
        var departamentoCounts = departamentoData.map(function(item) { return item.count; });

        var tipoSolicitudLabels = tipoSolicitudData.map(function(item) { return item.label; });
        var tipoSolicitudCounts = tipoSolicitudData.map(function(item) { return item.count; });

        var estadoLabels = estadoData.map(function(item) { return item.label; });
        var estadoCounts = estadoData.map(function(item) { return item.count; });

        // Gráfico de Tickets por Departamento
        var options1 = {
            chart: {
                type: 'pie'
            },
            series: departamentoCounts,
            labels: departamentoLabels
        }
        var chart1 = new ApexCharts(document.querySelector("#ticketsByDepartment"), options1);
        chart1.render();

        // Gráfico de Asuntos más Solicitados (Horizontal Bar Chart)
        var options2 = {
            chart: {
                type: 'bar',
                horizontal: true
            },
            series: [{
                data: tipoSolicitudData.map(function(item) { return { x: item.label, y: item.count }; })
            }]
        }
        var chart2 = new ApexCharts(document.querySelector("#ticketsBySubject"), options2);
        chart2.render();

        // Gráfico de Cantidad de Tickets por Tipo de Solicitud (Bullet Bar Chart)
        var options3 = {
            chart: {
                type: 'bar'
            },
            series: [{
                name: 'Cantidad de Tickets',
                data: tipoSolicitudCounts
            }],
            xaxis: {
                categories: tipoSolicitudLabels
            }
        }
        var chart3 = new ApexCharts(document.querySelector("#ticketsByType"), options3);
        chart3