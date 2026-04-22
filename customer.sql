-- SQL untuk tabel customer
CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_customer VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    telepon VARCHAR(20)
);