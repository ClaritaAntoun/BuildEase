<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['assignment_id']) || !isset($data['unused_quantity'])) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$assignmentId = $data['assignment_id'];
$unusedQuantity = $data['unused_quantity'];

try {
    // First get current unused quantity
    $stmt = $conn->prepare("SELECT quantity, unused_quantity FROM work_materials WHERE id = ?");
    $stmt->bind_param("i", $assignmentId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result) {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(['success' => false, 'message' => 'Assignment not found']);
        exit();
    }
    
    $currentQuantity = $result['quantity'];
    $currentUnused = $result['unused_quantity'];
    $newUnused = $currentUnused + $unusedQuantity;
    
    // Validate the requested unused quantity
    if ($newUnused > $currentQuantity) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['success' => false, 'message' => 'Cannot mark more materials as unused than were assigned']);
        exit();
    }
    
    // Update the unused quantity
    $updateStmt = $conn->prepare("UPDATE work_materials SET unused_quantity = ? WHERE id = ?");
    $updateStmt->bind_param("ii", $newUnused, $assignmentId);
    $updateStmt->execute();
    
    echo json_encode(['success' => true, 'message' => 'Materials marked as unused successfully']);
    
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>