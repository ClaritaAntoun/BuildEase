<?php
session_start();

// Check if user is logged in as contractor or admin
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

include 'conx.php'; // Database connection

// Get POST data
$projectId = $_POST['project_id'] ?? null;
$newStatus = $_POST['new_status'] ?? null;

// Validate input
if (!$projectId || !$newStatus) {
    die("Missing required parameters.");
}

$allowedStatuses = ['pending', 'active', 'completed'];
if (!in_array($newStatus, $allowedStatuses)) {
    die("Invalid status value.");
}

// Update the project status
$stmt = $conn->prepare("UPDATE project SET status = ? WHERE projectID = ?");
$stmt->bind_param("si", $newStatus, $projectId);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Project status updated successfully.";
} else {
    $_SESSION['error_message'] = "Failed to update project status.";
}

$stmt->close();
$conn->close();

// Redirect back to project details
header("Location: completed_project_details.php?id=" . $projectId);
exit();