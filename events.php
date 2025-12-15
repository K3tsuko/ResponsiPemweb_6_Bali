<?php
session_start();
require 'config/koneksi.php';

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);

    $query = "SELECT * FROM acara WHERE nama_acara LIKE '%$cari%' ORDER BY tanggal_acara ASC";
} else {
    $query = "SELECT * FROM acara ORDER BY tanggal_acara ASC";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DISCOVER BALINESE CULTURE - Events</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        :root {
            --color-primary-orange: #e67e22;
            --color-secondary-krem: #f5f0e1;
            --color-dark-text: #333;
            --color-light-text: #fff;
            --color-card-bg: #fff;
            --color-nav-bg-scrolled: rgba(51, 51, 51, 0.95);
            --color-hero-overlay: rgba(0, 0, 0, 0.2);
            --color-filter-bg: rgba(255, 255, 255, 0.8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--color-dark-text);
            background-color: var(--color-secondary-krem);
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--color-light-text);
            padding: 5px 10px;
            border: 1px solid var(--color-light-text);
            transition: color 0.3s, border-color 0.3s;
            text-decoration: none;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: var(--color-light-text);
            text-decoration: none;
            font-size: 0.9rem;
            opacity: 1;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            font-weight: 500;
        }

        .login-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .hero {
            position: relative;
            background: url('assets/Background/Background3.png') no-repeat center center;
            background-size: cover;
            height: 100vh;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--color-light-text);
            padding-top: 0;
            margin-bottom: 50px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            width: 90%;
            padding: 20px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 5px;
            letter-spacing: 1px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7), 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1rem;
            margin-bottom: 25px;
            font-weight: 400;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.8);
        }

        .search-bar {
            width: 100%;
            display: flex;
            margin-bottom: 30px;
        }

        .search-bar input {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 8px 0 0 8px;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            outline: none;
        }

        .search-btn {
            padding: 15px 25px;
            background-color: var(--color-primary-orange);
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background-color: #d35400;
        }

        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-buttons button {
            background-color: var(--color-filter-bg);
            color: var(--color-dark-text);
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .filter-buttons button.active {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            font-weight: 700;
        }

        .events-section {
            padding: 0 50px 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .event-card {
            background-color: var(--color-card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(230, 126, 34, 0.15);
            border-color: rgba(230, 126, 34, 0.3);
        }

        .event-image {
            width: 100%;
            height: 250px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: transform 0.6s ease;
        }

        .event-card:hover .event-image {
            transform: scale(1.1);
        }

        .event-details {
            padding: 20px;
            background: white;
            position: relative;
        }

        .event-details h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .price-text {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .price-text strong {
            font-weight: bold;
            color: var(--color-dark-text);
            font-size: 1rem;
        }

        .event-info-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .info-col {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-group {
            display: flex;
            align-items: center;
            color: #666;
            font-size: 0.85rem;
        }

        .info-group .material-icons {
            font-size: 16px;
            margin-right: 5px;
            color: var(--color-primary-orange);
        }

        .buy-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .buy-btn:hover {
            background-color: #d66a1a;
            transform: translateX(3px);
        }

        .event-card:hover .buy-btn {
            box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);
        }

        .buy-btn .material-icons {
            font-size: 16px;
            color: var(--color-light-text);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            gap: 10px;
        }

        .pagination a {
            text-decoration: none;
            color: var(--color-dark-text);
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-weight: 500;
        }

        .pagination a.active {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            font-weight: 700;
        }

        .pagination a:hover:not(.active) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }

            .nav-links,
            .login-btn {
                display: none;
            }

            .hero {
                height: 100vh;
            }

            .hero-content {
                width: 95%;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .events-section {
                padding: 0 20px 40px;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

            .event-image {
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav id="mainNav">
            <a href="index.php" class="logo"><img src="assets/Background/logo.png" alt="Logo" style="height: 40px;"></a>
            <div class="nav-right">
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="events.php">Explore Events</a>
                </div>
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login"): ?>
                    <a href="logout.php" class="login-btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="login-btn">Log In</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>DISCOVER BALINESE CULTURE</h1>
                <p>Find the perfect performance for your holiday</p>

                <form action="" method="GET" class="search-bar">
                    <input type="text" name="cari" placeholder="Search event name..."
                        value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>
        </section>

        <section class="events-section">
            <div class="events-grid">

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        $slug = strtolower(str_replace(' ', '-', $row['nama_acara']));
                        $image_path = "assets/event-images/" . $slug . ".jpg";

                        $formatted_date = date('F d, Y', strtotime($row['tanggal_acara']));
                        $formatted_time = date('g:i A', strtotime($row['waktu_acara']));
                        ?>
                        <div class="event-card">
                            <div class="event-image" style="background-image: url('<?php echo $image_path; ?>');"></div>
                            <div class="event-details">
                                <h3><?php echo htmlspecialchars($row['nama_acara']); ?></h3>
                                <p class="price-text">From: <strong>IDR
                                        <?php echo number_format($row['harga'], 0, ',', '.'); ?></strong></p>
                                <div class="event-info-footer">
                                    <div class="info-col">
                                        <div class="info-group">
                                            <span class="material-icons">event</span>
                                            <span><?php echo $formatted_date; ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="material-icons">schedule</span>
                                            <span><?php echo $formatted_time; ?></span>
                                        </div>
                                    </div>
                                    <a href="seatmap.php?id=<?php echo $row['id_acara']; ?>" class="buy-btn">
                                        Buy Now
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 20px;">
                        <h3>No events found for "<?php echo htmlspecialchars($_GET['cari']); ?>"</h3>
                        <p><a href="index.php" style="color: var(--color-primary-orange);">View all events</a></p>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const navbar = document.getElementById('mainNav');
            window.addEventListener('scroll', function () {
                if (window.scrollY > 50) {
                    navbar.style.backgroundColor = 'var(--color-nav-bg-scrolled)';
                } else {
                    navbar.style.backgroundColor = 'transparent';
                }
            });

            const filterBtns = document.querySelectorAll('.filter-buttons button');
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            const styleSheet = document.createElement("style");
            styleSheet.innerText = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(styleSheet);
        });
    </script>
</body>

</html>