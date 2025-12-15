<?php
session_start();
require 'config/koneksi.php';

// Check login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit;
}

// Get ticket IDs and event ID from URL
$ticket_ids_param = isset($_GET['tickets']) ? $_GET['tickets'] : '';
$id_acara = isset($_GET['event']) ? intval($_GET['event']) : 0;

if (empty($ticket_ids_param) || $id_acara == 0) {
    die("Invalid transaction data.");
}

// Parse ticket IDs
$ticket_ids = array_map('intval', explode(',', $ticket_ids_param));

// Fetch event info
$query_event = "SELECT * FROM acara WHERE id_acara = $id_acara";
$result_event = mysqli_query($conn, $query_event);
$event = mysqli_fetch_assoc($result_event);

if (!$event) {
    die("Event not found.");
}

$eventName = $event['nama_acara'];
$eventDate = date('d M Y', strtotime($event['tanggal_acara']));
$eventTime = date('H:i', strtotime($event['waktu_acara']));
$eventLocation = $event['lokasi_acara'];
$ticketPrice = $event['harga'];

// Fetch seat numbers for these tickets
$seat_numbers = [];
$placeholders = implode(',', array_fill(0, count($ticket_ids), '?'));
$query_seats = "SELECT k.nomor_kursi FROM tiket t 
                JOIN kursi k ON t.id_kursi = k.id_kursi 
                WHERE t.id_tiket IN ($placeholders)";
$stmt = $conn->prepare($query_seats);
$types = str_repeat('i', count($ticket_ids));
$stmt->bind_param($types, ...$ticket_ids);
$stmt->execute();
$result_seats = $stmt->get_result();

while ($row = $result_seats->fetch_assoc()) {
    $seat_numbers[] = $row['nomor_kursi'];
}

$total_price = count($seat_numbers) * $ticketPrice;
$user_name = $_SESSION['user_name'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Transaction</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F6F3E6; 
            padding-bottom: 50px; 
        }

        .header-section {
            background-color: #737373;
            color: white;
            padding-bottom: 60px;
        }

        .navbar {
            display: flex;
            justify-content: flex-end; 
            align-items: center;
            padding: 20px 50px;
            font-size: 12px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login {
            background-color: #E88A68; 
            padding: 8px 20px;
            border-radius: 4px;
            color: white !important;
            font-weight: 600;
        }

        .hero-text {
            text-align: center;
            padding-top: 40px;
        }

        .hero-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .hero-text p { font-size: 16px; opacity: 0.9; }

        .main-content {
            padding: 40px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .progress-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 40px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .progress-container {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 60px;
        }

        .step { text-align: center; z-index: 1; width: 25%; }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 20px;
            color: black;
        }

        .step-1 .icon-circle { background-color: #39E75F; } 
        .step-2 .icon-circle { background-color: #FFA500; } 
        .step-3 .icon-circle { background-color: #FF4D4D; } 
        .step-4 .icon-circle { background-color: #FF4D4D; } 

        .step h4 { font-size: 14px; margin-bottom: 5px; color: #222; font-weight: 700; }
        .step p { font-size: 11px; color: #666; line-height: 1.4; padding: 0 10px; }

        .transaction-card {
            background-color: #737373; 
            border-radius: 15px;
            padding: 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-left { flex: 1; padding-right: 20px; }

        .info-row { margin-bottom: 15px; font-size: 16px; }

        .label { display: inline-block; width: 160px; font-weight: 500; opacity: 0.9; }

        .badge {
            padding: 4px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-unpaid { background-color: #FF6B6B; color: white; }
        .badge-pending { background-color: #F4EA78; color: #333; }

        .message-box { margin-top: 25px; margin-bottom: 30px; }
        .message-box h5 { font-size: 16px; font-weight: 400; margin-bottom: 5px; opacity: 0.9; }
        .message-box p { font-size: 18px; font-weight: 600; line-height: 1.4; }

        .booking-details {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .booking-details p { margin-bottom: 8px; font-size: 14px; }

        .btn-confirm-payment {
            background-color: #39E75F;
            border: none;
            padding: 15px 30px;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-confirm-payment:hover { background-color: #2bc94d; }

        .card-right {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            width: 250px;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .qr-image { width: 100%; height: 100%; object-fit: contain; }

        @media (max-width: 768px) {
            .transaction-card { flex-direction: column-reverse; text-align: center; }
            .card-left { padding-right: 0; margin-top: 30px; }
            .label { width: auto; display: block; margin-bottom: 5px; }
        }
    </style>
</head>
<body>

    <div class="header-section">
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="events.php">Explore Events</a>
            <a href="logout.php" class="btn-login">Logout</a>
        </nav>
        
        <div class="hero-text">
            <h1>CONFIRMATION TRANSACTION</h1>
            <p>Please complete your payment</p>
        </div>
    </div>

    <div class="main-content">
        
        <h3 class="progress-title">Transaction Progress</h3>

        <div class="progress-container">
            <div class="step step-1">
                <div class="icon-circle"><i class="fas fa-shopping-bag"></i></div>
                <h4>Transaction Created</h4>
                <p>Booking successful</p>
            </div>
            <div class="step step-2">
                <div class="icon-circle"><i class="far fa-credit-card"></i></div>
                <h4>Payment</h4>
                <p>Awaiting payment</p>
            </div>
            <div class="step step-3">
                <div class="icon-circle"><i class="fas fa-microchip"></i></div>
                <h4>Processing</h4>
                <p>Verifying payment</p>
            </div>
            <div class="step step-4">
                <div class="icon-circle"><i class="fas fa-check"></i></div>
                <h4>Complete</h4>
                <p>Download E-Ticket</p>
            </div>
        </div>

        <div class="transaction-card">
            <div class="card-left">
                <div class="info-row">
                    <span class="label">Payment Status:</span>
                    <span class="badge badge-unpaid">UNPAID</span>
                </div>
                <div class="info-row">
                    <span class="label">Transaction Status:</span>
                    <span class="badge badge-pending">PENDING</span>
                </div>

                <div class="booking-details">
                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                    <p><strong>Event:</strong> <?php echo htmlspecialchars($eventName); ?></p>
                    <p><strong>Date:</strong> <?php echo $eventDate; ?> • <?php echo $eventTime; ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($eventLocation); ?></p>
                    <p><strong>Seats:</strong> <?php echo implode(', ', $seat_numbers); ?></p>
                    <p><strong>Total:</strong> IDR <?php echo number_format($total_price, 0, ',', '.'); ?></p>
                </div>

                <form action="confirm_payment.php" method="POST">
                    <input type="hidden" name="tickets" value="<?php echo $ticket_ids_param; ?>">
                    <input type="hidden" name="event" value="<?php echo $id_acara; ?>">
                    <button type="submit" class="btn-confirm-payment">
                        <i class="fas fa-check-circle"></i> CONFIRM PAYMENT
                    </button>
                </form>
            </div>

            <div class="card-right">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BALI-<?php echo implode('-', $ticket_ids); ?>" alt="QR Code" class="qr-image">
            </div>
        </div>

    </div>

</body>
</html>