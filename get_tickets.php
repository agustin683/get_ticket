<?php
session_start();
include('db.php');

// Verificar si el usuario es especialista
if ($_SESSION['role'] != 'specialist') {
    exit();
}

$username = $_SESSION['username'];

// Obtener información de los tickets asignados al especialista
$queryAsignados = "SELECT * FROM tickets WHERE assigned_to=(SELECT id FROM users WHERE username='$username') AND status != 'resolved'";
$resultAsignados = mysqli_query($conn, $queryAsignados);
$ticketsAsignados = [];
while ($row = mysqli_fetch_assoc($resultAsignados)) {
    $ticketsAsignados[] = $row;
}

// Obtener información de los tickets resueltos por el especialista
$queryResueltos = "SELECT * FROM tickets WHERE assigned_to=(SELECT id FROM users WHERE username='$username') AND status = 'resolved'";
$resultResueltos = mysqli_query($conn, $queryResueltos);
$ticketsResueltos = [];
while ($row = mysqli_fetch_assoc($resultResueltos)) {
    $ticketsResueltos[] = $row;
}

// Generar HTML para los tickets asignados
$asignadosHTML = "";
foreach ($ticketsAsignados as $ticket) {
    $asignadosHTML .= "<tr data-ticket-id='{$ticket['id']}'>";
    $asignadosHTML .= "<td>{$ticket['id']}</td>";
    $asignadosHTML .= "<td>{$ticket['created_at']}</td>";
    $asignadosHTML .= "<td>{$ticket['status']}</td>";
    $asignadosHTML .= "<td>{$ticket['department']}</