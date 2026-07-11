# 🐄 Moo-rah Rezeki | Sistem Cabutan Bertuah Farm Fresh Terengganu

Sistem web responsif dan interaktif yang direka khas untuk kempen Cabutan Bertuah "Moo-rah Rezeki" oleh **Farm Fresh Terengganu**. Sistem ini membolehkan pelanggan mendaftar penyertaan dengan memuat naik resit pembelian, berinteraksi di ruang sembang komuniti, serta membolehkan pihak pentadbir (admin) mengurus data peserta dan melakukan cabutan pemenang secara rawak secara langsung (live).

---

## 🚀 Ciri-Ciri Utama Sistem

### 1. Halaman Utama (Landing Page - `index.php`)
* **Countdown Timer:** Paparan masa berbaki yang dikemas kini setiap saat sebelum acara cabutan bertuah bermula.
* **Live Streaming Mockup:** Elemen video latar belakang yang menyerupai siaran langsung (Live) dengan kaunter jumlah penonton dan pautan terus ke TikTok rasmi (`@mr_mooooo`).
* **Sembang Komuniti (Live Chat):** Ruang sembang komuniti interaktif yang memaparkan mesej/komen daripada peserta. Data dikemas kini secara automatik setiap 5 saat menggunakan AJAX (Polling).

### 2. Pendaftaran Peserta (`daftar.php`)
* **Borang Penyertaan Selamat:** Pelanggan boleh mendaftar menggunakan Nama Penuh, No. Telefon, No. IC, dan No. Resit.
* **Auto-Formatting Input:** 
  * No. IC diformat secara automatik ke format standard (Contoh: `950102-11-5344`).
  * No. Resit diformat automatik dengan awalan `#` dan pembahagi sempang (Contoh: `#3-19244`).
* **Sekatan Resit Pendua:** Sistem menyemak pangkalan data dan menghalang pendaftaran resit yang sama bagi mengelakkan penipuan.
* **Muat Naik Fail:** Keupayaan memuat naik gambar resit (minimum RM32). Fail diubah nama secara automatik menggunakan timestamp unik untuk mengelakkan nama fail bertindih.
* **Modal Interaktif:** Pop-up modal moden untuk status pendaftaran (Berjaya/Gagal) tanpa memuat semula halaman (using Fetch API).

### 3. Sembang Komuniti (`komen.php` & `get_komen.php`)
* Peserta yang berjaya mendaftar boleh terus meninggalkan komen dorongan atau ucapan.
* Nama pengguna dimasukkan secara automatik daripada borang pendaftaran sebelumnya.
* Mempunyai had aksara (maksimum 300 aksara) bagi mengelakkan spam.

### 4. Panel Pentadbir (Admin Panel - `admin.php`)
* **Sistem Log Masuk Selamat:** Dilindungi dengan pengesahan sesi (Session Authentication) melalui `login.php`.
* **Statistik Dashboard:** Paparan ringkasan jumlah penyertaan keseluruhan, pendaftaran hari ini, jumlah pemenang semasa, dan baki peserta sedia cabut.
* **Carian Data:** Menapis peserta berdasarkan Nama, No. Telefon, No. IC, atau No. Resit secara pantas.
* **Pengurusan Data:** Melihat maklumat lengkap peserta termasuk fail resit yang dimuat naik, serta keupayaan untuk memadam entri (fail resit fizikal juga dipadamkan secara automatik dari pelayan).
* **Eksport CSV (`export.php`):** Eksport senarai peserta ke format CSV dengan sokongan UTF-8 BOM untuk keserasian paparan Microsoft Excel yang sempurna.
* **Cabutan Rawak Beranimasi (Lucky Draw Wheel):**
  * Memilih 1 pemenang secara rawak dari database menggunakan perintah `ORDER BY RAND()`.
  * Mengecualikan pemenang terdahulu secara automatik.
  * Paparan animasi pembukaan kotak hadiah (Giftbox Spin, Shake & Explode) yang dramatik beserta letupan confetti/starburst sebelum mendedahkan pemenang.
  * Pemenang disimpan terus ke dalam jadual `winners`.

---

## 🛠️ Stack Teknologi

* **Frontend:** HTML5, CSS3, Tailwind CSS (via CDN), Google Fonts (Plus Jakarta Sans & Inter), Material Symbols.
* **Backend:** PHP (Objek Oriented & Procedural).
* **Database:** MySQL / MariaDB.
* **Rangkaian/Asynchronous:** JavaScript (Fetch API & AJAX Polling).

---

## 🗄️ Struktur Pangkalan Data (MySQL)

Sila import skrip SQL berikut ke dalam MySQL database anda:

```sql
CREATE DATABASE IF NOT EXISTS cabutan_bertuah;
USE cabutan_bertuah;

-- 1. Jadual Pendaftaran Peserta
CREATE TABLE IF NOT EXISTS `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `ic_no` varchar(50) NOT NULL,
  `receipt_no` varchar(50) NOT NULL,
  `receipt_img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Jadual Sembang Komuniti
CREATE TABLE IF NOT EXISTS `komen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `mesej` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Jadual Pemenang Cabutan
CREATE TABLE IF NOT EXISTS `winners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `ic_no` varchar(20) DEFAULT NULL,
  `receipt_no` varchar(50) DEFAULT NULL,
  `won_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🔑 Kredensial Log Masuk Admin Default

* **URL:** `http://localhost/cabutan/login.php`
* **Username:** `admin`
* **Password:** `ff2026`

*(Nota: Password boleh diubah di dalam fail `login.php` pada baris ke-8)*
