CREATE DATABASE IF NOT EXISTS proyekakhir;
USE proyekakhir;

CREATE TABLE acara (
    id_acara INT PRIMARY KEY AUTO_INCREMENT,
    nama_acara VARCHAR(255) NOT NULL,
    tanggal_acara DATE NOT NULL,
    waktu_acara TIME NOT NULL,
    lokasi_acara VARCHAR(255),
    deskripsi_acara TEXT,
    harga DECIMAL(10,2) DEFAULT 0
);

CREATE TABLE kursi (
    id_kursi INT PRIMARY KEY AUTO_INCREMENT,
    id_acara INT NOT NULL,
    nomor_kursi INT NOT NULL,           -- Simple numeric: 1, 2, 3... 100
    status_kursi BOOLEAN DEFAULT 1,      -- 1 = sudah dipesan (if row exists, it's booked)
    FOREIGN KEY (id_acara) REFERENCES acara(id_acara)
);

CREATE TABLE pelanggan (
    id_pelanggan INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE tiket (
    id_tiket INT PRIMARY KEY AUTO_INCREMENT,
    id_pelanggan INT NOT NULL,
    id_kursi INT NOT NULL,
    id_acara INT NOT NULL,
    status_tiket BOOLEAN DEFAULT 0,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_kursi) REFERENCES kursi(id_kursi),
    FOREIGN KEY (id_acara) REFERENCES acara(id_acara)
);

-- DATA INSERTION

-- 1. Insert Events
INSERT INTO acara (id_acara, nama_acara, tanggal_acara, waktu_acara, lokasi_acara, deskripsi_acara, harga) VALUES
(1, 'Kecak Fire Dance', '2025-12-28', '18:00:00', 'Uluwatu Temple', 'A mesmerizing performance of the Ramayana epic.', 150000),
(2, 'Barong Dance', '2026-01-11', '09:30:00', 'Batubulan, Ubud', 'A storytelling dance narrating the fight between good and evil.', 100000),
(3, 'Legong Keraton Dance', '2026-01-12', '19:30:00', 'Puri Saren Ubud', 'A refined dance form taking inspiration from Java.', 100000),
(4, 'Jangger Dance', '2026-01-13', '19:00:00', 'Denpasar Art Center', 'A unique social dance created in the 1920s.', 100000),
(5, 'Ramayana Dance Drama', '2026-01-27', '19:00:00', 'Ubud Palace', 'The classic Hindu epic Ramayana.', 150000),
(6, 'Kebyar Duduk Dance', '2026-01-16', '19:00:00', 'Tabanan', 'A virtuosic dance where the performer dances mostly while seated.', 100000);

-- 2. Sample Booked Seats (numerical)
-- If a row exists in kursi, it means that seat is BOOKED for that event
INSERT INTO kursi (id_acara, nomor_kursi, status_kursi) VALUES
(1, 5, 1),  -- Seat 5 booked for Kecak
(1, 12, 1), -- Seat 12 booked for Kecak
(1, 45, 1); -- Seat 45 booked for Kecak
