<?php
session_start();

// Semak sama ada admin dah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

include 'db_config.php';

// Set header untuk download CSV
$tarikh_export = date('d-m-Y_H-i');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="Peserta_CabutanBertuah_' . $tarikh_export . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// BOM untuk pastikan Excel baca UTF-8 dengan betul
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

// Header lajur CSV
fputcsv($output, ['No.', 'Nama Penuh', 'No. Telefon', 'Nombor IC', 'No. Resit', 'Fail Gambar', 'Tarikh Daftar']);

// Ambil semua data
$result = $conn->query("SELECT * FROM entries ORDER BY created_at DESC");
$bil = 1;
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $bil++,
        $row['name'],
        $row['phone'],
        $row['ic_no'],
        $row['receipt_no'],
        $row['receipt_img'],
        date('d/m/Y H:i', strtotime($row['created_at']))
    ]);
}

fclose($output);
exit();
?>
