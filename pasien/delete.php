<?php
require_once '../config/db.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
        throw new Exception("ID tidak valid");
    }

    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM pasien WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data pasien berhasil dihapus'
            ]);
        } else {
            throw new Exception("Data tidak ditemukan");
        }
    } else {
        throw new Exception("Gagal menghapus data");
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    $conn->close();
    exit();
}
