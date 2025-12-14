<?php
session_start();
require 'config/koneksi.php';

// 1. Get Event ID
$id_acara = isset($_GET['id']) ? intval($_GET['id']) : 1;

// 2. Fetch Event Details
$query_event = "SELECT * FROM acara WHERE id_acara = $id_acara";
$result_event = mysqli_query($conn, $query_event);
$event = mysqli_fetch_assoc($result_event);

if (!$event) {
    die("Event not found.");
}

$ticketPrice = $event['harga'];
$eventName = $event['nama_acara'];
$eventTime = date('H:i', strtotime($event['waktu_acara']));

// 3. Fetch Booked Seats from DB (numerical)
$bookedSeats = [];
$query_kursi = "SELECT nomor_kursi FROM kursi WHERE id_acara = $id_acara";
$result_kursi = mysqli_query($conn, $query_kursi);

while ($row = mysqli_fetch_assoc($result_kursi)) {
    $bookedSeats[] = intval($row['nomor_kursi']);
}

// Config
$totalSeats = 100; // Simple: 100 numbered seats
$seatsPerRow = 10; // Display 10 seats per row
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo htmlspecialchars($eventName); ?></title>
    <style>
        :root {
            --primary-color: #E67E22;
            --primary-dark: #D35400;
            --seat-available: #FDEBD0;
            --seat-sold: #95A5A6;
            --seat-selected: #C0392B;
            --bg-color: #FFF5E6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .booking-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            max-width: 700px;
            width: 100%;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header .home-btn {
            background: transparent;
            border: 1px solid white;
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }

        .screen {
            background: linear-gradient(90deg, #5D4037, #8D6E63);
            height: 30px;
            width: 80%;
            margin: 0 auto 25px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .seats-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 8px;
            margin-bottom: 25px;
        }

        .seat {
            aspect-ratio: 1;
            background-color: var(--seat-available);
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: #666;
            border: 2px solid #ddd;
            transition: all 0.2s;
        }

        .seat:hover:not(.sold) {
            transform: scale(1.1);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .seat.sold {
            background-color: var(--seat-sold);
            color: #fff;
            cursor: not-allowed;
            border-color: #7f8c8d;
        }

        .seat.selected {
            background-color: var(--seat-selected);
            color: white;
            border-color: #922B21;
        }

        .info-panel {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 20px;
            background: #fafafa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .legend {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .legend-marker {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #ddd;
        }

        .event-info {
            flex: 1;
            min-width: 200px;
        }

        .event-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .event-info strong {
            color: var(--primary-color);
        }

        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: var(--seat-available);
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .summary .total {
            font-size: 20px;
            font-weight: bold;
            color: #5D4037;
        }

        .btn-checkout {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 126, 34, 0.4);
        }
    </style>
</head>

<body>
    <div class="booking-container">
        <div class="header">
            <h2><?php echo htmlspecialchars($eventName); ?></h2>
            <a href="events.php" class="home-btn">← Back</a>
        </div>

        <div class="screen">STAGE</div>

        <div class="seats-grid">
            <?php for ($i = 1; $i <= $totalSeats; $i++): ?>
                <?php $isSold = in_array($i, $bookedSeats) ? 'sold' : ''; ?>
                <div class="seat <?php echo $isSold; ?>" data-seat="<?php echo $i; ?>">
                    <?php echo $i; ?>
                </div>
            <?php endfor; ?>
        </div>

        <div class="info-panel">
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-marker" style="background: var(--seat-available);"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-marker" style="background: var(--seat-sold);"></div>
                    <span>Sold</span>
                </div>
                <div class="legend-item">
                    <div class="legend-marker" style="background: var(--seat-selected);"></div>
                    <span>Selected</span>
                </div>
            </div>
            <div class="event-info">
                <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($event['tanggal_acara'])); ?></p>
                <p><strong>Time:</strong> <?php echo $eventTime; ?></p>
                <p><strong>Price:</strong> IDR <?php echo number_format($ticketPrice, 0, ',', '.'); ?> / seat</p>
            </div>
        </div>

        <div class="summary">
            <div>
                <span>Selected: <strong id="selectedCount">0</strong> seats</span>
            </div>
            <div class="total">
                IDR <span id="totalPrice">0</span>
            </div>
        </div>

        <button class="btn-checkout" onclick="checkout()">Book Now</button>
    </div>

    <script>
        const ticketPrice = <?php echo $ticketPrice; ?>;
        const seatsGrid = document.querySelector('.seats-grid');
        const selectedCountEl = document.getElementById('selectedCount');
        const totalPriceEl = document.getElementById('totalPrice');

        seatsGrid.addEventListener('click', (e) => {
            const seat = e.target.closest('.seat');
            if (seat && !seat.classList.contains('sold')) {
                seat.classList.toggle('selected');
                updateTotal();
            }
        });

        function updateTotal() {
            const selected = document.querySelectorAll('.seat.selected');
            const count = selected.length;
            selectedCountEl.textContent = count;
            totalPriceEl.textContent = (count * ticketPrice).toLocaleString('id-ID');
        }

        function checkout() {
            const selected = document.querySelectorAll('.seat.selected');
            if (selected.length === 0) {
                alert('Please select at least one seat.');
                return;
            }

            const seats = Array.from(selected).map(s => s.dataset.seat);
            alert('Booking seats: ' + seats.join(', ') + '\nTotal: IDR ' + (selected.length * ticketPrice).toLocaleString('id-ID'));
            // Here you would submit to a checkout/payment page
        }
    </script>
</body>

</html>