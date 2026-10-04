-- =====================================================================
-- SISTEM RENTAL MOTOR - DATABASE SCHEMA
-- Database: MariaDB / MySQL
-- Jalankan file ini terlebih dahulu sebelum menjalankan aplikasi.
--
-- CATATAN: Aplikasi ini menggunakan LOGIN BYPASS (admin/admin123 di-hardcode
-- langsung di login.php, tanpa cek ke database), jadi tabel "users" TIDAK
-- diperlukan di versi ini.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS db_rental_motor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_rental_motor;

-- Tabel Motor (Data Utama Aset Kendaraan)
CREATE TABLE IF NOT EXISTS motor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plat_nomor VARCHAR(15) NOT NULL UNIQUE,
    merk_model VARCHAR(100) NOT NULL,
    tipe ENUM('Matic', 'Manual', 'Sport', 'Bebek') NOT NULL DEFAULT 'Matic',
    tahun YEAR NULL,
    harga_per_hari DECIMAL(12,2) NOT NULL,
    status ENUM('Tersedia', 'Disewa', 'Maintenance') NOT NULL DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Penyewa (Data Pendukung / Master Data Pelanggan)
CREATE TABLE IF NOT EXISTS penyewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    no_ktp VARCHAR(20) NOT NULL UNIQUE,
    no_hp VARCHAR(20) NOT NULL,
    alamat TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Sewa (Core System - Transaksi Sewa per Motor)
CREATE TABLE IF NOT EXISTS sewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    motor_id INT NOT NULL,
    penyewa_id INT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_rencana_selesai DATE NOT NULL,
    tanggal_kembali DATE NULL,
    total_hari INT NULL,
    total_biaya DECIMAL(12,2) NULL,
    status ENUM('Berlangsung', 'Selesai') NOT NULL DEFAULT 'Berlangsung',
    catatan VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sewa_motor FOREIGN KEY (motor_id) REFERENCES motor(id) ON DELETE RESTRICT,
    CONSTRAINT fk_sewa_penyewa FOREIGN KEY (penyewa_id) REFERENCES penyewa(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Contoh data motor
INSERT INTO motor (plat_nomor, merk_model, tipe, tahun, harga_per_hari, status) VALUES
('B 2211 XYZ', 'Honda Beat', 'Matic', 2023, 75000, 'Tersedia'),
('B 3322 XYZ', 'Yamaha NMAX', 'Matic', 2022, 120000, 'Tersedia'),
('B 4433 XYZ', 'Honda Vario 160', 'Matic', 2023, 90000, 'Tersedia'),
('B 5544 XYZ', 'Kawasaki Ninja', 'Sport', 2021, 200000, 'Tersedia');

-- Contoh data penyewa
INSERT INTO penyewa (nama_lengkap, no_ktp, no_hp, alamat) VALUES
('Rizky Ramadhan', '3271010101950001', '081234567890', 'Jl. Anggrek No. 12, Jakarta'),
('Putri Wulandari', '3271020202960002', '081234567891', 'Jl. Dahlia No. 8, Bandung');
