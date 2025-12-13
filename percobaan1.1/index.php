<?php
session_start();
// We DO NOT redirect here anymore, because we want guests to see the landing page.
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
            background-color: #fff;
        }

        /* Navigasi & Header Semantik */
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

        /* Kelas yang ditambahkan oleh JS saat di-scroll */
        nav.scrolled {
            background-color: var(--color-nav-bg-scrolled);
            padding: 10px 50px;
        }

        /* Kontainer untuk link dan tombol login */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
            /* Jarak antara nav-links dan tombol login */
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--color-light-text);
            padding: 5px 10px;
            border: 1px solid var(--color-light-text);
            transition: color 0.3s, border-color 0.3s;
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

        /* Styling Tombol Login */
        .login-btn {
            background-color: var(--color-primary-orange);
            /* Diubah ke warna primary untuk kontras */
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

        /* Hamburger Menu (Hanya terlihat di mobile) */
        .hamburger-menu {
            display: none;
            cursor: pointer;
        }

        .hamburger-menu .material-icons {
            color: var(--color-light-text);
            font-size: 30px;
        }

        /* --- Bagian Lain (Hero, Popular Section, Footer) Sama seperti sebelumnya --- */
        .hero {
            position: relative;
            height: 90vh;
            background: url('assets/Background1.jpg') no-repeat center center/cover;
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
            max-width: 700px;
            padding: 20px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .explore-btn {
            background-color: var(--color-primary-orange);
            color: var(--color-light-text);
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            text-transform: uppercase;
        }

        /* Popular Events Section */
        .popular-section {
            background-color: var(--color-secondary-krem);
            padding: 50px 50px;
        }

        .popular-section h2 {
            font-size: 1.8rem;
            margin-bottom: 30px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-dark-text);
            text-align: left;
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

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
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


        /* Footer */
        footer {
            background-color: var(--color-dark-text);
            color: var(--color-light-text);
            padding: 30px 50px;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Media Query untuk Responsif */
        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }

            /* 1. Aktifkan Hamburger dan Sembunyikan Nav-Links Default */
            .hamburger-menu {
                display: block;
            }

            /* Sembunyikan Nav-Links default dan Tombol Login di mobile */
            .nav-links,
            .login-btn-desktop {
                display: none;
            }

            /* Layout Mobile Menu */
            .nav-links {
                flex-direction: column;
                position: absolute;
                top: 50px;
                left: 0;
                width: 100%;
                background-color: var(--color-nav-bg-scrolled);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                padding: 10px 0;
                gap: 10px;
            }

            /* Kelas yang ditambahkan JS untuk menampilkan menu */
            .nav-links.active {
                display: flex;
            }

            .nav-links a {
                padding: 10px 20px;
                text-align: center;
                width: 100%;
            }

            /* Tampilkan Tombol Login di dalam menu mobile */
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
                            <a href="/checkout/kecak" class="buy-btn">Buy Now</a>
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
                            <a href="/checkout/barong" class="buy-btn">Buy Now</a>
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
                            <a href="/checkout/legong" class="buy-btn">Buy Now</a>
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
            // Memilih semua tautan yang mengarah ke ID di halaman
            const allLinks = document.querySelectorAll('a[href^="#"], .explore-btn');

            // 1. Efek Scroll Navigasi Sticky
            window.addEventListener('scroll', function () {
                if (window.scrollY > 100) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });

            // 2. Toggle Menu Hamburger
            hamburger.addEventListener('click', function () {
                navLinks.classList.toggle('active');
                if (navLinks.classList.contains('active')) {
                    menuIcon.textContent = 'close';
                } else {
                    menuIcon.textContent = 'menu';
                }
            });

            // 3. Smooth Scroll 
            allLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId.startsWith('#')) {
                        e.preventDefault();

                        // Tutup menu mobile setelah klik (jika terbuka)
                        if (navLinks.classList.contains('active')) {
                            navLinks.classList.remove('active');
                            menuIcon.textContent = 'menu';
                        }

                        document.querySelector(targetId).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>