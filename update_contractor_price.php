<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

$projectId = $_POST['project_id'] ?? null;
$contractorPrice = $_POST['contractor_price'] ?? null;

if ($projectId && is_numeric($contractorPrice)) {
    $stmt = $conn->prepare("UPDATE project SET contractorPrice = ? WHERE projectID = ?");
    $stmt->bind_param("di", $contractorPrice, $projectId);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Contractor price updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update contractor price.";
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid input.";
}

header("Location: project_details.php?id=" . $projectId);
exit();
?>