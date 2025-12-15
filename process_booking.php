<?php
session_start();
require 'config/koneksi.php';

header('Content-Type: application/json');

// 1. Check Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    echo json_encode(['success' => false, 'message' => 'Please log in to book tickets.']);
    exit;
}

// 2. Validate Input
if (!isset($_POST['id_acara']) || !isset($_POST['seats'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

$id_acara = intval($_POST['id_acara']);
$seats = json_decode($_POST['seats'], true);
$id_pelanggan = $_SESSION['user_id'];

if (empty($seats) || !is_array($seats)) {
    echo json_encode(['success' => false, 'message' => 'No seats selected.']);
    exit;
}

// 3. Process Booking (Transaction)
mysqli_begin_transaction($conn);

try {
    // Check for duplicates first (Double Booking Prevention)
    $placeholders = implode(',', array_fill(0, count($seats), '?'));
    $types = str_repeat('i', count($seats));

    // Check if any of these seats are already booked for this event
    // Note: We need to bind parameters dynamically
    $check_sql = "SELECT nomor_kursi FROM kursi WHERE id_acara = ? AND nomor_kursi IN ($placeholders)";
    $stmt_check = $conn->prepare($check_sql);

    // Bind id_acara and seat numbers
    $bind_params = array_merge([$id_acara], $seats);
    $bind_types = 'i' . $types;
    $stmt_check->bind_param($bind_types, ...$bind_params);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $taken_seats = [];
        while ($row = $result_check->fetch_assoc()) {
            $taken_seats[] = $row['nomor_kursi'];
        }
        throw new Exception('Seats already taken: ' . implode(', ', $taken_seats));
    }

    // Insert into 'kursi' and 'tiket'
    $stmt_kursi = $conn->prepare("INSERT INTO kursi (id_acara, nomor_kursi, status_kursi) VALUES (?, ?, 1)");
    $stmt_tiket = $conn->prepare("INSERT INTO tiket (id_pelanggan, id_kursi, id_acara, status_tiket) VALUES (?, ?, ?, 0)");

    $ticket_ids = [];

    foreach ($seats as $seat_num) {
        $seat_num = intval($seat_num);

        // a. Insert Seat
        $stmt_kursi->bind_param("ii", $id_acara, $seat_num);
        if (!$stmt_kursi->execute()) {
            throw new Exception("Failed to book seat $seat_num");
        }
        $new_kursi_id = $conn->insert_id;

        // b. Insert Ticket (status_tiket = 0 means unpaid)
        $stmt_tiket->bind_param("iii", $id_pelanggan, $new_kursi_id, $id_acara);
        if (!$stmt_tiket->execute()) {
            throw new Exception("Failed to generate ticket for seat $seat_num");
        }
        $ticket_ids[] = $conn->insert_id;
    }

    mysqli_commit($conn);
    echo json_encode(['success' => true, 'ticket_ids' => $ticket_ids]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>