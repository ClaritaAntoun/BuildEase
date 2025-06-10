<?php
session_start();
include 'conx.php';

// Check if user is logged in as contractor
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

// Get project ID and step number from URL
$projectId = $_GET['id'] ?? null;
$stepNumber = $_GET['step'] ?? null;

if (!$projectId || !$stepNumber) {
    die("Invalid parameters.");
}

// Reactivate the step
$stmt = $conn->prepare("UPDATE work_in SET is_active = 1, deactivated_at = NULL 
                        WHERE projectID = ? AND stepNumber = ? AND stepType = 'custom'");
$stmt->bind_param("ii", $projectId, $stepNumber);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['success_message'] = "Custom step reactivated successfully.";
} else {
    $_SESSION['error_message'] = "Failed to reactivate custom step or step not found.";
}

$stmt->close();
ob_end_clean();

header("Location: project_Details.php?id=" . $projectId);
exit();
?>