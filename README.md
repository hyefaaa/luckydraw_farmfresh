# 🐄 Moo-rah Rezeki | Lucky Draw System - Farm Fresh Terengganu

A responsive and interactive web system designed for the "Moo-rah Rezeki" Lucky Draw campaign by **Farm Fresh Terengganu**. This system allows customers to submit lucky draw entries by uploading purchase receipts, participate in a community live chat, and enables administrators to manage entries and perform animated random winner draws live.

---

## 🚀 Key Features

### 1. Landing Page (`index.php`)
* **Countdown Timer:** A real-time countdown showing the days, hours, minutes, and seconds remaining before the live draw.
* **Live Streaming Mockup:** An embedded background video mimicking a live stream with viewer counts and a direct link to the official TikTok channel (`@mr_mooooo`).
* **Community Live Chat:** An interactive community feed loading participant comments. The chat feed updates asynchronously every 5 seconds using AJAX polling.

### 2. Registration Page (`daftar.php`)
* **Secure Submission Form:** Customers can register using their Full Name, Phone Number, IC Number, and Receipt Number.
* **Auto-Formatting Inputs:** 
  * IC Number automatically formats to standard pattern (e.g., `950102-11-5344`).
  * Receipt Number automatically prefixes with `#` and hyphen separators (e.g., `#3-19244`).
* **Duplicate Receipt Prevention:** The system checks the database to prevent duplicate registrations of the same receipt number.
* **Receipt Upload (Min RM32):** Built-in file upload validation. Uploaded receipt images are automatically renamed with a unique timestamp to prevent file collision.
* **Asynchronous Modals:** Seamless registration status modals (Success/Error) using the JavaScript Fetch API, eliminating the need to refresh the page.

### 3. Community Commenting (`komen.php` & `get_komen.php`)
* Registered customers can post words of encouragement and wishes.
* The user's name is auto-filled from the registration session.
* Comments are character-limited (max 300 characters) to prevent spam.

### 4. Admin Panel (`admin.php`)
* **Secure Login:** Protected by PHP session authentication through `login.php`.
* **Dashboard Metrics:** Displays summary counts of total entries, today's registrations, total winners drawn, and remaining eligible entries.
* **Search & Filter:** Instantly filter and search participants by Name, Phone Number, IC Number, or Receipt Number.
* **Entry Management:** View participant details and uploaded receipts, and delete entries (which automatically cleans up the physical receipt file from the server).
* **CSV Export (`export.php`):** Export the participant database to a CSV file equipped with UTF-8 BOM, ensuring correct display on Microsoft Excel.
* **Animated Lucky Draw Randomizer:**
  * Randomly selects a single winner using MySQL `ORDER BY RAND() LIMIT 1`.
  * Automatically excludes prior winners from subsequent draws.
  * Engaging UI containing a gift box opening animation (Spin, Shake & Explode) with confetti and starburst visual effects before revealing the winner.
  * Winner data is automatically stored in a dedicated `winners` table.

---

## 🛠️ Tech Stack

* **Frontend:** HTML5, CSS3, Tailwind CSS (via CDN), Google Fonts (Plus Jakarta Sans & Inter), Material Symbols.
* **Backend:** PHP (Object-Oriented & Procedural).
* **Database:** MySQL / MariaDB.
* **Networking/Asynchronous:** JavaScript (Fetch API & AJAX Polling).

---

## 🗄️ Database Schema (MySQL)

You can import the SQL script below to set up your database:

```sql
CREATE DATABASE IF NOT EXISTS cabutan_bertuah;
USE cabutan_bertuah;

-- 1. Participant Entries Table
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

-- 2. Community Comments Table
CREATE TABLE IF NOT EXISTS `komen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `mesej` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Winners Table
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

## 🔑 Default Admin Credentials

* **URL:** `http://localhost/cabutan/login.php`
* **Username:** `admin`
* **Password:** `xxxxxxx`

*(Note: The admin credentials can be updated on line 8 in the `login.php` file)*
