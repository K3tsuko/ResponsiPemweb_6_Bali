<?php
session_start();
require 'config/koneksi.php';

// Check login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit;
}

// Validate input
if (!isset($_POST['tickets']) || !isset($_POST['event'])) {
    die("Invalid request.");
}

$ticket_ids_param = $_POST['tickets'];
$id_acara = intval($_POST['event']);
$ticket_ids = array_map('intval', explode(',', $ticket_ids_param));

// Update ticket status to PAID (status_tiket = 1)
$placeholders = implode(',', array_fill(0, count($ticket_ids), '?'));
$update_sql = "UPDATE tiket SET status_tiket = 1 WHERE id_tiket IN ($placeholders)";
$stmt = $conn->prepare($update_sql);
$types = str_repeat('i', count($ticket_ids));
$stmt->bind_param($types, ...$ticket_ids);
$stmt->execute();

// Redirect to e-ticket page
header("Location: eticket.php?tickets=" . $ticket_ids_param . "&event=" . $id_acara);
exit;
?>