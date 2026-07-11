<?php
include 'db_config.php';
header('Content-Type: application/json');

// =============================================
// HANTAR KOMEN BARU (POST)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($conn->real_escape_string($_POST['nama'] ?? ''));
    $mesej = trim($conn->real_escape_string($_POST['mesej'] ?? ''));

    if ($nama === '' || $mesej === '') {
        echo json_encode(['status' => 'error', 'mesej' => 'Nama dan mesej tidak boleh kosong.']);
        exit();
    }

    if (strlen($mesej) > 300) {
        echo json_encode(['status' => 'error', 'mesej' => 'Mesej terlalu panjang (maksimum 300 aksara).']);
        exit();
    }

    $sql = "INSERT INTO komen (nama, mesej) VALUES ('$nama', '$mesej')";
    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'mesej' => 'Gagal simpan komen.']);
    }
    exit();
}

// =============================================
// AMBIL SENARAI KOMEN (GET)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil 30 komen terkini
    $result = $conn->query("SELECT nama, mesej, created_at FROM komen ORDER BY created_at DESC LIMIT 30");
    $komen_list = [];

    while ($row = $result->fetch_assoc()) {
        $komen_list[] = [
            'nama'       => htmlspecialchars($row['nama']),
            'mesej'      => htmlspecialchars($row['mesej']),
            'masa'       => date('h:i A', strtotime($row['created_at']))
        ];
    }

    // Balikkan supaya terkini di bawah (seperti chat)
    echo json_encode(array_reverse($komen_list));
    exit();
}
?>
