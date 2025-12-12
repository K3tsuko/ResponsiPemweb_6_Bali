<?php
session_start();
require 'config/koneksi.php';

// 1. QUERY DATABASE
// Pastikan nama tabel sesuai database kamu (misal: 'events' atau 'tabel_event')
$query = "SELECT * FROM acara";
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

        /* Navigasi & Header */
        header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
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
            opacity: 0.9;
        }

        .login-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #d66a1a;
        }

        /* Hero Section */
        .hero {
            position: relative;
            background: url('https://picsum.photos/1600/900?nature') no-repeat center center/cover;
            height: 450px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--color-light-text);
            padding-top: 100px;
            margin-bottom: 50px;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1;
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
        }

        .hero p {
            font-size: 1rem;
            margin-bottom: 25px;
            font-weight: 300;
        }

        /* Search Bar */
        .search-bar {
            width: 100%;
            display: flex;
            margin-bottom: 30px;
        }

        .search-bar input {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            outline: none;
        }

        /* Filter Buttons */
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
        }

        .filter-buttons button.active {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            font-weight: 700;
        }

        /* Events Grid Section */
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
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .event-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .event-details {
            padding: 15px;
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
        }

        .info-group {
            display: flex;
            align-items: center;
            color: #666;
            font-size: 0.9rem;
        }

        .info-group .material-icons {
            font-size: 18px;
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
            transition: background-color 0.3s;
        }

        .buy-btn:hover {
            background-color: #d66a1a;
        }

        .buy-btn .material-icons {
            font-size: 16px;
            color: var(--color-light-text);
        }

        /* Pagination */
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
                height: 350px;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <header>
        <nav id="mainNav">
            <a href="index.php" class="logo">LOGO</a>
            <div class="nav-right">
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="events.php">Explore Events</a>
                    <a href="index.php#about">About Us</a>
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

                <div class="search-bar">
                    <input type="text" placeholder="Search event name...">
                </div>

                <div class="filter-buttons">
                    <button class="active">All</button>
                    <button>Dance</button>
                    <button>Music</button>
                    <button>Ritual</button>
                </div>
            </div>
        </section>

        <section class="events-section">
            <div class="events-grid">

                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // SET DEFAULT IMAGE IF NONE EXISTS
                        $gambar = $row['gambar'] ? $row['gambar'] : 'https://picsum.photos/400/180';
                        ?>

                        <div class="event-card">
                            <div class="event-image" style="background-image: url('img/<?php echo $gambar; ?>');"></div>

                            <div class="event-details">
                                <h3><?php echo $row['nama_acara']; ?></h3>

                                <p class="price-text">From: <strong>IDR <?php echo number_format($row['harga']); ?></strong></p>

                                <div class="event-info-footer">
                                    <div class="info-group">
                                        <span class="material-icons">schedule</span>
                                        <span><?php echo $row['waktu_acara']; ?></span>
                                    </div>

                                    <a href="detail_event.php?id=<?php echo $row['id_acara']; ?>" class="buy-btn">
                                        Buy Now
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php
                    } // End While
                } else {
                    echo "<p style='text-align:center; grid-column: 1/-1;'>No events found.</p>";
                }
                ?>
            </div>

            <div class="pagination">
                <a href="#"><span class="material-icons">chevron_left</span></a>
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#"><span class="material-icons">chevron_right</span></a>
            </div>
        </section>
    </main>

</body>

</html>