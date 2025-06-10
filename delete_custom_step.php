<?php
// Start output buffering
ob_start();

// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__.'/conx.php';

// Verify contractor is logged in
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

// Validate inputs
$projectId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stepNumber = filter_input(INPUT_GET, 'step', FILTER_VALIDATE_INT);

if (!$projectId || !$stepNumber) {
    $_SESSION['error'] = "Invalid parameters.";
    header("Location: project_details.php?id=".$projectId);
    exit();
}

try {
    // Update the step to inactive
    $stmt = $conn->prepare("UPDATE work_in SET is_active = 0, deactivated_at = NOW() 
                           WHERE projectID = ? AND stepNumber = ? AND stepType = 'custom'");
    $stmt->bind_param("ii", $projectId, $stepNumber);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Custom step deactivated successfully.";
    } else {
        $_SESSION['error'] = "Failed to deactivate custom step.";
    }
    
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

// Clear output buffer and redirect
ob_end_clean();
header("Location: project_details.php?id=".$projectId);
exit();
?>