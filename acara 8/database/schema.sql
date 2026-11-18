-- OPSIONAL: hanya jika database si_akademik belum ada / tabelnya belum lengkap.
-- Aman dijalankan ulang (IF NOT EXISTS), tidak menimpa data yang sudah ada.
CREATE DATABASE IF NOT EXISTS si_akademik CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE si_akademik;

CREATE TABLE IF NOT EXISTS prodi (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS mahasiswa (
    id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim      VARCHAR(20)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    email    VARCHAR(100) NOT NULL,
    prodi_id INT UNSIGNED NOT NULL,
    angkatan YEAR         NOT NULL,
    status   ENUM('aktif','cuti','lulus') NOT NULL DEFAULT 'aktif',
    FOREIGN KEY (prodi_id) REFERENCES prodi(id)
);

INSERT INTO prodi (nama)
SELECT * FROM (SELECT 'Teknik Informatika' AS nama UNION SELECT 'Manajemen Informatika' UNION SELECT 'Teknik Komputer') t
WHERE NOT EXISTS (SELECT 1 FROM prodi);
