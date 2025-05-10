<?php
require_once '../config/db.php';
header('Content-Type: application/json');

try {
    // Validasi ID
    if (!isset($_GET['id'])) { // <-- TAMBAHKAN KURUNG TUTUP DI SINI
        throw new Exception("Parameter ID tidak valid");
    }

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false || $id < 1) {
        throw new Exception("ID tidak valid");
    }

    // Prepared statement untuk delete
    $stmt = $conn->prepare("DELETE FROM paramedik WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Data tidak ditemukan");
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Data paramedik berhasil dihapus'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
