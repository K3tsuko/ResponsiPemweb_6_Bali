<?php
session_start();
require 'config/koneksi.php'; 

$id_acara = isset($_GET['id']) ? intval($_GET['id']) : 1;

$query_event = "SELECT * FROM acara WHERE id_acara = $id_acara";
$result_event = mysqli_query($conn, $query_event);
$event = mysqli_fetch_assoc($result_event);

if (!$event) {
    die("Event tidak ditemukan dalam database.");
}

$eventName = $event['nama_acara'];
$ticketPrice = $event['harga'];
$eventLocation = $event['lokasi_acara'];
$eventDescription = $event['deskripsi_acara'];

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];
} else {
    $selectedDate = date('Y-m-d', strtotime($event['tanggal_acara']));
}
$displayDate = date('d M Y', strtotime($selectedDate));

$slug = strtolower(str_replace(' ', '-', $eventName));
$image_path = "assets/event-images/" . $slug . ".jpg"; 

$eventTime = date('H:i A', strtotime($event['waktu_acara']));

$bookedSeats = [];
$query_kursi = "SELECT nomor_kursi FROM kursi WHERE id_acara = $id_acara"; 
$result_kursi = mysqli_query($conn, $query_kursi);

while ($row = mysqli_fetch_assoc($result_kursi)) {
    $bookedSeats[] = intval($row['nomor_kursi']);
}

$totalSeats = 100;
$seatsPerRow = 10;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo htmlspecialchars($eventName); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F2F0E4;
            --accent-color: #D97D64;
            --text-dark: #2C3E50;
            --seat-avail-bg: #FFFFFF;
            --seat-selected: #E67E22;
            --card-bg: #FFF5F2;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            padding-bottom: 50px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            background: transparent;
            color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }

        .navbar .logo { font-weight: bold; padding: 5px 15px; border: 1px solid #fff; border-radius: 4px; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }

        .hero {
            width: 100%;
            height: 350px;
            background-color: #333;
            overflow: hidden;
            position: relative;
        }
        
        .hero img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            filter: brightness(0.7); 
        }
        
        .container { max-width: 1000px; margin: 0 auto; padding: 30px 20px; }

        .event-header h1 { font-size: 36px; margin-bottom: 20px; font-weight: 700; }
        .event-meta { display: flex; flex-direction: column; gap: 10px; margin-bottom: 30px; font-size: 18px; font-weight: 600; }
        .meta-item { display: flex; align-items: center; gap: 10px; }

        .description {
            background: #FFF;
            padding: 25px;
            border-radius: 12px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .terms-box {
            background-color: var(--card-bg);
            border: 1px solid #EAD4CC;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            color: #444;
        }

        .terms-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .terms-header h3 { font-size: 1.25rem; font-weight: 700; color: #000; margin: 0; }
        .terms-list { padding-left: 20px; margin: 0; }
        .terms-list li { margin-bottom: 8px; font-size: 0.95rem; line-height: 1.5; }

        .booking-wrapper {
            background-color: var(--accent-color);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(217, 125, 100, 0.3);
        }

        .booking-controls {
            display: flex;
            justify-content: space-between;
            background: rgba(0,0,0,0.1);
            padding: 15px 30px;
            color: white;
            font-weight: 600;
        }
        .date-badge { background: rgba(255,255,255,0.3); padding: 5px 15px; border-radius: 4px; font-size: 14px; }

        .booking-content { display: flex; flex-wrap: wrap; padding: 30px; gap: 30px; }

        .stage-area { flex: 2; min-width: 300px; }
        .stage-label {
            background: #C4A484; color: white; text-align: center;
            padding: 8px; margin-bottom: 30px; font-weight: bold;
            letter-spacing: 2px; border-radius: 4px; width: 80%; margin: 0 auto;
        }

        .seats-grid {
            display: grid;
            grid-template-columns: auto repeat(10, 1fr);
            gap: 8px; justify-content: center;
        }

        .row-label { display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 12px; width: 20px; }

        .seat {
            aspect-ratio: 1; background-color: var(--seat-avail-bg);
            border-radius: 4px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: bold; color: var(--accent-color);
            transition: transform 0.2s;
        }
        .seat:hover:not(.sold) { transform: scale(1.1); }
        .seat.sold { background-color: rgba(0,0,0,0.2); color: rgba(255,255,255,0.5); cursor: not-allowed; }
        .seat.selected { background-color: var(--text-dark); color: #fff; border: 2px solid #fff; }

        .booking-summary {
            flex: 1; min-width: 250px; background: white;
            border-radius: 8px; padding: 20px;
            display: flex; flex-direction: column; justify-content: space-between;
            min-height: 300px;
        }
        .btn-confirm {
            width: 100%; background: #C4A484; color: white; border: none;
            padding: 12px; margin-top: 10px; font-weight: bold; cursor: pointer; border-radius: 4px;
        }
        .btn-confirm:hover { filter: brightness(0.9); }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">LOGO</div>
        <div class="nav-links">
            <a href="index.php">Home</a> <a href="events.php">Explore Events</a>
        </div>
    </div>

    <div class="hero">
        <img src="<?php echo $image_path; ?>" alt="Event Banner - <?php echo htmlspecialchars($eventName); ?>" 
             onerror="this.onerror=null; this.src='assets/event-images/default.jpg';"> 
    </div>

    <div class="container">
        
        <div class="event-header">
            <h1><?php echo htmlspecialchars($eventName); ?></h1>
            
            <div class="event-meta">
                <div class="meta-item">
                    <span>📅</span> 
                    <span><?php echo $displayDate; ?> • <?php echo $eventTime; ?></span>
                </div>
                <div class="meta-item">
                    <span>📍</span> 
                    <span><?php echo htmlspecialchars($eventLocation); ?></span>
                </div>
            </div>
        </div>

        <div class="description">
            <p><?php echo nl2br(htmlspecialchars($eventDescription)); ?></p>
        </div>

        <div class="terms-box">
            <div class="terms-header">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <h3>Terms & Conditions</h3>
            </div>
            <ul class="terms-list">
                <li>One ticket is valid for one person.</li>
                <li>The ticket is valid for a single entry only.</li>
                <li>Purchased tickets are non-refundable.</li>
                <li>No outside food and beverages.</li>
                <li>Pets are not allowed.</li>
            </ul>
        </div>

        <div class="booking-wrapper">
            <div class="booking-controls">
                
                <div class="control-item" style="position: relative; display: flex; align-items: center;">
                    <span style="pointer-events: none; z-index: 1;">
                        Date: <span class="date-badge" id="dateDisplay"><?php echo $displayDate; ?></span>
                    </span>
                    
                    <input type="date" 
                           id="datePicker" 
                           value="<?php echo $selectedDate; ?>"
                           min="<?php echo date('Y-m-d'); ?>" 
                           onchange="changeDate(this)"
                           onclick="try{this.showPicker()}catch(e){}" 
                           style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; opacity: 0; z-index: 10; cursor: pointer;">
                </div>

                <div class="control-item">
                    <span>Price: IDR <?php echo number_format($ticketPrice, 0, ',', '.'); ?></span>
                </div>
            </div>

            <div class="booking-content">
                <div class="stage-area">
                    <div class="stage-label">STAGE</div>
                    
                    <div class="seats-grid">
                        <?php 
                        $rowLabels = range('A', 'J'); 
                        $currentRowIndex = 0;

                        for ($i = 1; $i <= $totalSeats; $i++): 
                            if (($i - 1) % $seatsPerRow == 0) {
                                echo '<div class="row-label">' . $rowLabels[$currentRowIndex] . '</div>';
                                $currentRowIndex++;
                            }
                            $isSold = in_array($i, $bookedSeats) ? 'sold' : ''; 
                        ?>
                            <div class="seat <?php echo $isSold; ?>" data-seat="<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="booking-summary">
                    <div>
                        <h4 style="margin-bottom:10px;">BOOKING SUMMARY</h4>
                        <p style="font-size:14px; color:#666;">Date: <strong><?php echo $displayDate; ?></strong></p>
                        <p style="font-size:14px; color:#666;">Seats: <span id="seat-list-text">-</span></p>
                    </div>

                    <div>
                        <div style="background:#F9F9F9; padding:15px; margin-bottom:10px; border-radius:6px;">
                            <div style="font-size:12px; color:#888;">Total Price</div>
                            <div style="font-size:18px; font-weight:800; color:#2C3E50;">IDR <span id="totalDisplay">0</span></div>
                        </div>
                        <button class="btn-confirm" onclick="checkout()">CONFIRM BOOKING</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ticketPrice = <?php echo $ticketPrice; ?>;
        const eventId = <?php echo $id_acara; ?>;
        const selectedDate = "<?php echo $selectedDate; ?>";
        
        const seatsGrid = document.querySelector('.seats-grid');
        const totalDisplayEl = document.getElementById('totalDisplay');
        const seatListTextEl = document.getElementById('seat-list-text');

        function changeDate(input) {
            const newDate = input.value;
            if (newDate) {
                const currentUrl = new URL(window.location.href);
                
                currentUrl.searchParams.set('date', newDate);
                
                currentUrl.searchParams.set('id', eventId);
                
                window.location.href = currentUrl.toString();
            }
        }

        seatsGrid.addEventListener('click', (e) => {
            const seat = e.target.closest('.seat');
            if (seat && !seat.classList.contains('sold') && !seat.classList.contains('row-label')) {
                seat.classList.toggle('selected');
                updateSummary();
            }
        });

        function updateSummary() {
            const selected = document.querySelectorAll('.seat.selected');
            const count = selected.length;
            const total = count * ticketPrice;
            totalDisplayEl.textContent = total.toLocaleString('id-ID');
            
            if(count > 0) {
                const seatNumbers = Array.from(selected).map(s => s.dataset.seat).join(', ');
                seatListTextEl.textContent = seatNumbers;
            } else {
                seatListTextEl.textContent = "-";
            }
        }

        function checkout() {
            const selected = document.querySelectorAll('.seat.selected');
            if (selected.length === 0) {
                alert('Pilih kursi terlebih dahulu!');
                return;
            }
            const seats = Array.from(selected).map(s => s.dataset.seat);
            const total = selected.length * ticketPrice;

            if (confirm(`Booking ${seats.length} kursi pada tanggal ${selectedDate} seharga IDR ${total.toLocaleString('id-ID')}?`)) {
                const formData = new FormData();
                formData.append('id_acara', eventId);
                formData.append('seats', JSON.stringify(seats));
                formData.append('date', selectedDate);

                fetch('process_booking.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Booking Berhasil!');
                        location.reload(); 
                    } else {
                        alert('Booking Gagal: ' + (data.message || 'Error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem.');
                });
            }
        }
    </script>
</body>
</html>