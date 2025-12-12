<?php
session_start();
require 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events - Bali Events</title>

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
        }

        /* Reset Dasar */
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

        /* Navigasi & Header (SAME AS INDEX) */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            background-color: var(--color-nav-bg-scrolled);
            /* Always dark on this page */
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--color-light-text);
            padding: 5px 10px;
            border: 1px solid var(--color-light-text);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: var(--color-light-text);
            text-decoration: none;
            font-size: 0.9rem;
            opacity: 0.9;
            transition: opacity 0.3s;
        }

        .nav-links a:hover {
            opacity: 1;
        }

        .login-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .login-btn:hover {
            background-color: #d66a1a;
        }

        .login-btn-mobile {
            display: none;
        }

        .hamburger-menu {
            display: none;
            cursor: pointer;
        }

        .hamburger-menu .material-icons {
            color: var(--color-light-text);
            font-size: 30px;
        }

        /* EVENTS HEADER */
        .page-header {
            padding-top: 100px;
            padding-bottom: 30px;
            text-align: center;
            background-color: var(--color-dark-text);
            color: var(--color-light-text);
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            text-transform: uppercase;
        }

        /* EVENTS GRID */
        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .event-card {
            background-color: var(--color-card-bg);
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .event-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .kecak {
            background-image: url('https://picsum.photos/400/180?blur=5&random=1');
        }

        .barong {
            background-image: url('https://picsum.photos/400/180?blur=5&random=2');
        }

        .legong {
            background-image: url('https://picsum.photos/400/180?blur=5&random=3');
        }

        .event-details {
            padding: 15px 15px 10px 15px;
        }

        .event-title {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: var(--color-dark-text);
        }

        .price-text {
            color: var(--color-primary-orange);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .event-info {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #666;
        }

        .event-info-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .event-info-item .material-icons {
            font-size: 16px;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .event-genre {
            font-size: 0.8rem;
            color: #999;
            font-style: italic;
        }

        .buy-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.1s;
        }

        .buy-btn:hover {
            background-color: #d66a1a;
        }


        /* Footer */
        footer {
            background-color: var(--color-dark-text);
            color: var(--color-light-text);
            padding: 30px 50px;
            text-align: center;
            font-size: 0.9rem;
            margin-top: 50px;
        }

        /* Media Query */
        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }

            .hamburger-menu {
                display: block;
            }

            .nav-links,
            .login-btn-desktop {
                display: none;
            }

            .nav-links {
                flex-direction: column;
                position: absolute;
                top: 60px;
                left: 0;
                width: 100%;
                background-color: var(--color-nav-bg-scrolled);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                padding: 10px 0;
                gap: 10px;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links a {
                padding: 10px 20px;
                text-align: center;
                width: 100%;
            }

            .nav-links a.login-btn-mobile {
                display: block;
                background-color: var(--color-primary-orange);
            }
        }
    </style>
</head>

<body>
    <header>
        <nav id="mainNav">
            <a href="index.php" class="logo">LOGO</a>
            <div class="nav-right">
                <div class="nav-links" id="navLinks">
                    <a href="index.php">Home</a>
                    <a href="events.php">Explore Events</a>
                    <a href="index.php#about">About Us</a>

                    <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login"): ?>
                        <a href="logout.php" class="login-btn-mobile">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="login-btn-mobile">Log In</a>
                    <?php endif; ?>
                </div>

                <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login"): ?>
                    <a href="logout.php" class="login-btn login-btn-desktop">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="login-btn login-btn-desktop">Log In</a>
                <?php endif; ?>

                <div class="hamburger-menu">
                    <span class="material-icons" id="menuIcon">menu</span>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-header">
        <h1>Upcoming Events</h1>
        <p>Discover the best traditional experiences in Bali</p>
    </div>

    <main class="events-container">
        <div class="events-grid">

            <!-- Event 1 -->
            <div class="event-card">
                <div class="event-image kecak" role="img" aria-label="Gambar Tari Kecak"></div>
                <div class="event-details">
                    <h3 class="event-title">Kecak Fire Dance</h3>
                    <p class="price-text">From: <strong>IDR 150.000</strong></p>
                    <div class="event-info">
                        <div class="event-info-item">
                            <span class="material-icons">schedule</span>
                            <span>6:00 PM</span>
                        </div>
                        <div class="event-info-item">
                            <span class="material-icons">place</span>
                            <span>Uluwatu</span>
                        </div>
                    </div>
                    <div class="event-footer">
                        <span class="event-genre">#sunset-show</span>
                        <a href="#" class="buy-btn">Buy Now</a>
                    </div>
                </div>
            </div>

            <!-- Event 2 -->
            <div class="event-card">
                <div class="event-image barong" role="img" aria-label="Gambar Tari Barong"></div>
                <div class="event-details">
                    <h3 class="event-title">Barong Dance</h3>
                    <p class="price-text">From: <strong>IDR 100.000</strong></p>
                    <div class="event-info">
                        <div class="event-info-item">
                            <span class="material-icons">schedule</span>
                            <span>9:30 AM</span>
                        </div>
                        <div class="event-info-item">
                            <span class="material-icons">place</span>
                            <span>Ubud</span>
                        </div>
                    </div>
                    <div class="event-footer">
                        <span class="event-genre">#folklore</span>
                        <a href="#" class="buy-btn">Buy Now</a>
                    </div>
                </div>
            </div>

            <!-- Event 3 -->
            <div class="event-card">
                <div class="event-image legong" role="img" aria-label="Gambar Tari Legong"></div>
                <div class="event-details">
                    <h3 class="event-title">Legong Keraton Dance</h3>
                    <p class="price-text">From: <strong>IDR 100.000</strong></p>
                    <div class="event-info">
                        <div class="event-info-item">
                            <span class="material-icons">schedule</span>
                            <span>7:30 PM</span>
                        </div>
                        <div class="event-info-item">
                            <span class="material-icons">place</span>
                            <span>Puri Saren</span>
                        </div>
                    </div>
                    <div class="event-footer">
                        <span class="event-genre">#royal-dance</span>
                        <a href="#" class="buy-btn">Buy Now</a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <p>© 2025 Bali Dance Events. All Rights Reserved.</p>
        <p>Contact: info@balidance.com | +62 812-XXXX-XXXX</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.getElementById('navLinks');
            const hamburger = document.querySelector('.hamburger-menu');
            const menuIcon = document.getElementById('menuIcon');

            // Toggle Menu Hamburger
            hamburger.addEventListener('click', function () {
                navLinks.classList.toggle('active');
                if (navLinks.classList.contains('active')) {
                    menuIcon.textContent = 'close';
                } else {
                    menuIcon.textContent = 'menu';
                }
            });
        });
    </script>
</body>

</html>