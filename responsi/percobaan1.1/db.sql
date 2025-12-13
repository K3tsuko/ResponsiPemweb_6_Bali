-- Database name: proyekakhir

CREATE TABLE acara (
    id_acara INT PRIMARY KEY AUTO_INCREMENT,
    nama_acara VARCHAR(255) NOT NULL,
    tanggal_acara DATE NOT NULL,
    waktu_acara TIME NOT NULL,
    lokasi_acara VARCHAR(255),
    deskripsi_acara TEXT
);

CREATE TABLE kursi (
    id_kursi INT PRIMARY KEY AUTO_INCREMENT,
    id_acara INT NOT NULL,
    status_kursi BOOLEAN DEFAULT 0,      -- 0 = kosong, 1 = sudah dipesan
    harga DECIMAL(10,2),                 -- bisa dihapus jika tidak diperlukan
    FOREIGN KEY (id_acara) REFERENCES acara(id_acara)
);

CREATE TABLE pelanggan (
    id_pelanggan INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NULL,              -- boleh kosong
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL       -- hash password
);

CREATE TABLE tiket (
    id_tiket INT PRIMARY KEY AUTO_INCREMENT,
    id_pelanggan INT NOT NULL,
    id_kursi INT NOT NULL,
    id_acara INT NOT NULL,
    status_tiket BOOLEAN DEFAULT 0,      -- 0 = belum dibayar, 1 = sudah dibayar
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_kursi) REFERENCES kursi(id_kursi),
    FOREIGN KEY (id_acara) REFERENCES acara(id_acara)
);
