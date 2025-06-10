<?php
session_start();
include 'conx.php';

// Check if user is logged in
if (!isset($_SESSION['contractor_identity'])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Validate input
if (!isset($_POST['material_id']) || !is_numeric($_POST['material_id'])) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['success' => false, 'message' => 'Invalid material ID']);
    exit();
}

$contractorId = $_SESSION['contractor_identity']['id'];
$materialId = $_POST['material_id'];

// Check if the material belongs to this contractor
$checkSql = "SELECT id FROM material_library WHERE id = ? AND contractorID = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("ii", $materialId, $contractorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'You are not authorized to edit this material']);
    exit();
}

// Prepare update statement
$updateSql = "UPDATE material_library SET 
                title = ?, 
                category = ?, 
                price = ?, 
                supplier = ?, 
                unit_measure = ?, 
                description = ? 
              WHERE id = ? AND contractorID = ?";

$stmt = $conn->prepare($updateSql);
$stmt->bind_param(
    "ssdsssii",
    $_POST['title'],
    $_POST['category'],
    $_POST['price'],
    $_POST['supplier'],
    $_POST['unit_measure'],
    $_POST['description'],
    $materialId,
    $contractorId
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>