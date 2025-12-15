<?php
session_start();
require 'config/koneksi.php';

// Check login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit;
}

// Get ticket IDs and event ID
$ticket_ids_param = isset($_GET['tickets']) ? $_GET['tickets'] : '';
$id_acara = isset($_GET['event']) ? intval($_GET['event']) : 0;

if (empty($ticket_ids_param) || $id_acara == 0) {
    die("Invalid ticket data.");
}

$ticket_ids = array_map('intval', explode(',', $ticket_ids_param));

// Fetch event info
$query_event = "SELECT * FROM acara WHERE id_acara = $id_acara";
$result_event = mysqli_query($conn, $query_event);
$event = mysqli_fetch_assoc($result_event);

if (!$event) {
    die("Event not found.");
}

$eventName = $event['nama_acara'];
$eventDate = date('l, d F Y', strtotime($event['tanggal_acara']));
$eventTime = date('H:i', strtotime($event['waktu_acara']));
$eventLocation = $event['lokasi_acara'];

// Fetch ticket details
$tickets = [];
$placeholders = implode(',', array_fill(0, count($ticket_ids), '?'));
$query_tickets = "SELECT t.id_tiket, k.nomor_kursi, t.status_tiket 
                  FROM tiket t 
                  JOIN kursi k ON t.id_kursi = k.id_kursi 
                  WHERE t.id_tiket IN ($placeholders)";
$stmt = $conn->prepare($query_tickets);
$types = str_repeat('i', count($ticket_ids));
$stmt->bind_param($types, ...$ticket_ids);
$stmt->execute();
$result_tickets = $stmt->get_result();

while ($row = $result_tickets->fetch_assoc()) {
    $tickets[] = $row;
}

$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_id = $_SESSION['user_id'];

// Get slug for image
$slug = strtolower(str_replace(' ', '-', $eventName));
$image_path = "assets/event-images/" . $slug . ".jpg";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - <?php echo htmlspecialchars($eventName); ?></title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F6F3E6;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .success-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background-color: #39E75F;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            color: white;
        }

        .success-header h1 {
            font-family: 'Playfair Display', serif;
            color: #333;
            margin-bottom: 10px;
        }

        .success-header p {
            color: #666;
        }

        .ticket-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .ticket-header {
            background: linear-gradient(135deg, #E88A68, #D97A5F);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .ticket-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .ticket-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .ticket-body {
            padding: 30px;
        }

        .ticket-image {
            width: 100%;
            height: 150px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-item label {
            display: block;
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .info-item p {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .seats-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .seats-section h4 {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .seat-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .seat-badge {
            background: linear-gradient(135deg, #E88A68, #D97A5F);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            border-top: 2px dashed #eee;
        }

        .qr-section img {
            width: 150px;
            height: 150px;
            margin-bottom: 10px;
        }

        .qr-section p {
            font-size: 12px;
            color: #999;
        }

        .ticket-id {
            font-family: monospace;
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #E88A68, #D97A5F);
            color: white;
        }

        .btn-secondary {
            background: #333;
            color: white;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .ticket-card {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1>Payment Successful!</h1>
            <p>Your e-ticket is ready</p>
        </div>

        <div class="ticket-card">
            <div class="ticket-header">
                <h2><?php echo htmlspecialchars($eventName); ?></h2>
                <p>Balinese Cultural Performance</p>
            </div>

            <div class="ticket-body">
                <div class="ticket-image" style="background-image: url('<?php echo $image_path; ?>');"></div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Customer Name</label>
                        <p><?php echo htmlspecialchars($user_name); ?></p>
                    </div>
                    <div class="info-item">
                        <label>Customer ID</label>
                        <p>#<?php echo $user_id; ?></p>
                    </div>
                    <div class="info-item">
                        <label>Date</label>
                        <p><?php echo $eventDate; ?></p>
                    </div>
                    <div class="info-item">
                        <label>Time</label>
                        <p><?php echo $eventTime; ?></p>
                    </div>
                    <div class="info-item" style="grid-column: 1/-1;">
                        <label>Location</label>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($eventLocation); ?></p>
                    </div>
                </div>

                <div class="seats-section">
                    <h4>Your Seats (<?php echo count($tickets); ?> tickets)</h4>
                    <div class="seat-badges">
                        <?php foreach ($tickets as $ticket): ?>
                            <div class="seat-badge">
                                Seat <?php echo $ticket['nomor_kursi']; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="qr-section">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BALI-TICKET-<?php echo implode('-', $ticket_ids); ?>"
                        alt="QR Code">
                    <p>Scan this QR code at the venue</p>
                    <div class="ticket-id">Ticket ID: <?php echo implode(', ', $ticket_ids); ?></div>
                </div>
            </div>
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Ticket
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
    </div>

</body>

</html>