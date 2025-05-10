<?php
include '../config/db.php';

// Validasi ID
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: periksa.php?error=invalid_id");
    exit;
}

try {
    // Cek apakah data ada sebelum menghapus
    $check_stmt = $conn->prepare("SELECT id FROM periksa WHERE id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();

    if ($check_stmt->get_result()->num_rows === 0) {
        header("Location: periksa.php?error=not_found");
        exit;
    }

    // Eksekusi penghapusan
    $delete_stmt = $conn->prepare("DELETE FROM periksa WHERE id = ?");
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        header("Location: periksa.php?success=delete");
    } else {
        header("Location: periksa.php?error=delete_failed");
    }

    $delete_stmt->close();
} catch (Exception $e) {
    header("Location: periksa.php?error=" . urlencode($e->getMessage()));
}

$conn->close();
exit;
