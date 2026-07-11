<?php
// 1. Panggil fail sambungan database
include 'db_config.php';

// Pastikan respon dihantar dalam format teks bersih
header('Content-Type: text/plain');

// 2. Semak jika ada data dihantar melalui kaedah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari borang dan bersihkan untuk elak SQL Injection
    $nama       = $conn->real_escape_string($_POST['name']);
    $phone      = $conn->real_escape_string($_POST['phone']);
    $ic_no      = $conn->escape_string($_POST['ic_no']); 
    $receipt_no = trim($conn->real_escape_string($_POST['receipt_no'])); // trim untuk buang space kosong
    
    // =================================================================
    // KOD BARU: SEKATAN JIKA NOMBOR RESIT SAMA (DUPLICATE)
    // =================================================================
    $semak_resit_sql = "SELECT id FROM entries WHERE receipt_no = '$receipt_no'";
    $hasil_semak = $conn->query($semak_resit_sql);

    if ($hasil_semak->num_rows > 0) {
        // Jika jumpa nombor resit yang sama dalam database
        echo "Nombor resit ini (#$receipt_no) telah didaftarkan sebelum ini. Sila semak semula nombor resit anda.";
        exit(); // Hentikan proses di sini, jangan simpan!
    }
    // =================================================================

    // 3. Proses muat naik gambar resit
    $folder_tujuan  = "uploads/";
    
    // Pastikan folder uploads wujud
    if (!is_dir($folder_tujuan)) {
        mkdir($folder_tujuan, 0755, true);
    }

    $nama_asal_fail = $_FILES["receipt"]["name"];
    $extensi        = pathinfo($nama_asal_fail, PATHINFO_EXTENSION);
    
    // Namakan semula fail (Contoh: RESIT_0123456789_1715072000.jpg)
    $nama_fail_baru = "RESIT_" . $phone . "_" . time() . "." . $extensi;
    $laluan_fail    = $folder_tujuan . $nama_fail_baru;

    // 4. Pindahkan gambar dari memori sementara ke folder 'uploads'
    if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $laluan_fail)) {
        
        // 5. Simpan maklumat ke dalam database
        $sql = "INSERT INTO entries (name, phone, ic_no, receipt_no, receipt_img) 
                VALUES ('$nama', '$phone', '$ic_no', '$receipt_no', '$nama_fail_baru')";
        
        if ($conn->query($sql)) {
            echo "success"; // Ini akan dibaca oleh JavaScript di index.php
        } else {
            echo "Ralat Database: " . $conn->error;
        }
    } else {
        echo "Gagal memuat naik gambar resit. Pastikan folder 'uploads' wujud.";
    }
}
?>