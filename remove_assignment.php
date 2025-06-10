<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit();
}

$assignmentId = $_POST['assignment_id'] ?? null;

if (!$assignmentId) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['success' => false, 'message' => 'Missing assignment ID']);
    exit();
}

// Verify the assignment belongs to this contractor's project
$verifySql = "SELECT am.id 
              FROM assigned_materials am
              JOIN material_library ml ON am.materialID = ml.id
              WHERE am.id = ? AND ml.contractorID = ?";
$stmt = $conn->prepare($verifySql);
$stmt->bind_param("ii", $assignmentId, $_SESSION['contractor_identity']['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Not authorized to remove this assignment']);
    exit();
}

// Remove assignment
$deleteSql = "DELETE FROM assigned_materials WHERE id = ?";
$stmt = $conn->prepare($deleteSql);
$stmt->bind_param("i", $assignmentId);
$success = $stmt->execute();

if ($success) {
    echo json_encode(['success' => true]);
} else {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>