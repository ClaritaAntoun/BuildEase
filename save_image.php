<?php
session_start();
include "conx.php";
$projectID = isset($_POST['projectID']) ? $_POST['projectID'] : null;

if (!$projectID) {
    header("HTTP/1.1 400 Bad Request");
    die(json_encode(['success' => false, 'message' => 'Project ID not provided']));
}

try {
  
    $checkStmt = $conn->prepare("SELECT projectID FROM project WHERE projectID = ?");
    $checkStmt->bind_param("i", $projectID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        throw new Exception("Project not found");
    }

  
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("No image uploaded or upload error");
    }
    
    $uploadDir = "generated_images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
   
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = 'ai_project_' . $projectID . '_' . time() . '.' . $extension;
    $filePath = $uploadDir . $filename;
    
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
        throw new Exception("Failed to save image");
    }
    

    $updateStmt = $conn->prepare("UPDATE project SET imageGenerated = ? WHERE projectID = ?");
    $updateStmt->bind_param("si", $filePath, $projectID);
    
    if (!$updateStmt->execute()) {
        throw new Exception("Failed to update project with image");
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Image saved successfully',
        'imagePath' => $filePath
    ]);
    
} catch (Exception $e) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>