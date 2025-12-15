<?php
session_start();
require 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witness the Magic of Bali - Event Tickets</title>

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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--color-dark-text);
            background-color: #fff;
        }

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
            padding: 15px 50px;
            background-color: transparent;
            transition: background-color 0.3s, padding 0.3s;
        }

        nav.scrolled {
            background-color: var(--color-nav-bg-scrolled);
            padding: 10px 50px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
            transition: all 0.3s ease;
        }

        .logo:hover {
            background-color: var(--color-light-text);
            color: var(--color-dark-text);
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
            position: relative;
            opacity: 0.9;
            transition: opacity 0.3s;
            padding-bottom: 5px;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--color-primary-orange);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            opacity: 1;
        }

        .login-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #d66a1a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(230, 126, 34, 0.4);
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

        .hero {
            position: relative;
            height: 100vh;
            background: url('assets/Background/Background1.png') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--color-light-text);
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 20px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            margin-bottom: 15px;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            font-weight: 300;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .explore-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            display: inline-block;
        }

        .explore-btn:hover {
            background-color: #d66a1a;
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 25px rgba(230, 126, 34, 0.4);
        }

        .popular-section {
            background-color: var(--color-secondary-krem);
            padding: 80px 50px;
        }

        .popular-section h2 {
            font-size: 2rem;
            margin-bottom: 40px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-dark-text);
            text-align: center; 
            letter-spacing: 1px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .event-card {
            background-color: var(--color-card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: left;
            position: relative;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(230, 126, 34, 0.15);
            border-color: rgba(230, 126, 34, 0.3);
        }

        .event-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s ease;
        }

        .event-card:hover .event-image {
            transform: scale(1.1);
        }

        .kecak { background-image: url('assets/event-images/kecak-fire-dance.jpg'); }
        .barong { background-image: url('assets/event-images/barong-dance.jpg'); }
        .legong { background-image: url('assets/event-images/legong-keraton-dance.jpg'); }

        .event-details {
            padding: 25px;
        }

        .event-title {
            font-size: 1.4rem;
            margin-bottom: 5px;
            color: var(--color-dark-text);
        }

        .event-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
            margin-top: 15px;
        }

        .event-info-item {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            color: #666;
            gap: 10px;
        }

        .event-info-item .material-icons {
            font-size: 20px;
            color: var(--color-primary-orange);
        }

        .price-text {
            color: #666;
            font-size: 0.9rem;
        }

        .price-text strong {
            font-size: 1.1rem;
            color: var(--color-dark-text);
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .event-genre {
            font-size: 0.85rem;
            color: #999;
            font-style: italic;
        }

        .buy-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .buy-btn:hover {
            background-color: #d66a1a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
        }

        footer {
            background-color: var(--color-dark-text);
            color: var(--color-light-text);
            padding: 40px 50px;
            text-align: center;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            nav { padding: 15px 20px; }
            .hero h1 { font-size: 2.2rem; }
            .popular-section { padding: 50px 20px; }
            
            .hamburger-menu { display: block; }
            .nav-links, .login-btn-desktop { display: none; }

            .nav-links {
                flex-direction: column;
                position: absolute;
                top: 60px; 
                left: 0;
                width: 100%;
                background-color: var(--color-nav-bg-scrolled);
                padding: 20px 0;
                gap: 15px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            }

            .nav-links.active { display: flex; }
            .nav-links a { width: 100%; text-align: center; padding: 10px 0; }
            .nav-links a:hover { background-color: rgba(255,255,255,0.05); }
            
            .nav-links a.login-btn-mobile {
                display: inline-block;
                width: auto;
                background-color: var(--color-primary-orange);
                padding: 10px 30px;
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
                    <a href="#about">About Us</a>

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

    <main>
        <section class="hero" id="home" role="region" aria-label="Halaman Utama Promosi Tari Bali">
            <div class="hero-content">
                <h1>WITNESS THE MAGIC OF BALI</h1>
                <p>Secure your seat for the most mesmerizing traditional dances.</p>
                <a href="events.php" class="explore-btn">EXPLORE EVENTS</a>
            </div>
        </section>

        <section class="popular-section" id="events" role="region" aria-label="Acara Populer Minggu Ini">
            <h2>POPULAR THIS WEEK!</h2>
            <div class="events-grid">

                <div class="event-card">
                    <div class="event-image kecak" role="img" aria-label="Gambar Tari Kecak"></div>
                    <div class="event-details">
                        <h3 class="event-title">Kecak Fire Dance</h3>
                        <p class="price-text">From: <strong>IDR 150.000</strong></p>
                        <div class="event-info">
                            <div class="event-info-item">
                                <span class="material-icons">event</span>
                                <span>December 28, 2025</span>
                            </div>
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
                            <a href="seatmap.php?id=1" class="buy-btn">Buy Now</a>
                        </div>
                    </div>
                </div>

                <div class="event-card">
                    <div class="event-image barong" role="img" aria-label="Gambar Tari Barong"></div>
                    <div class="event-details">
                        <h3 class="event-title">Barong Dance</h3>
                        <p class="price-text">From: <strong>IDR 100.000</strong></p>
                        <div class="event-info">
                            <div class="event-info-item">
                                <span class="material-icons">event</span>
                                <span>January 11, 2026</span>
                            </div>
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
                            <a href="seatmap.php?id=2" class="buy-btn">Buy Now</a>
                        </div>
                    </div>
                </div>

                <div class="event-card">
                    <div class="event-image legong" role="img" aria-label="Gambar Tari Legong"></div>
                    <div class="event-details">
                        <h3 class="event-title">Legong Keraton Dance</h3>
                        <p class="price-text">From: <strong>IDR 100.000</strong></p>
                        <div class="event-info">
                            <div class="event-info-item">
                                <span class="material-icons">event</span>
                                <span>January 12, 2026</span>
                            </div>
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
                            <a href="seatmap.php?id=3" class="buy-btn">Buy Now</a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 Bali Dance Events. All Rights Reserved.</p>
        <p>Contact: info@balidance.com | +62 812-XXXX-XXXX</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nav = document.getElementById('mainNav');
            const navLinks = document.getElementById('navLinks');
            const hamburger = document.querySelector('.hamburger-menu');
            const menuIcon = document.getElementById('menuIcon');
            const allLinks = document.querySelectorAll('a[href^="#"], .explore-btn');

            window.addEventListener('scroll', function () {
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });

            hamburger.addEventListener('click', function () {
                navLinks.classList.toggle('active');
                if (navLinks.classList.contains('active')) {
                    menuIcon.textContent = 'close';
                } else {
                    menuIcon.textContent = 'menu';
                }
            });

            allLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId.startsWith('#')) {
                        e.preventDefault();

                        if (navLinks.classList.contains('active')) {
                            navLinks.classList.remove('active');
                            menuIcon.textContent = 'menu';
                        }

                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            targetElement.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>