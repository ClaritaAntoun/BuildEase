<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

$projectId = $_POST['project_id'] ?? null;
$websiteCommission = $_POST['website_commission'] ?? null;

if ($projectId && is_numeric($websiteCommission)) {
    $stmt = $conn->prepare("UPDATE project SET websiteCommission = ? WHERE projectID = ?");
    $stmt->bind_param("di", $websiteCommission, $projectId);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Website commission updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update website commission.";
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid input.";
}

header("Location: project_details.php?id=" . $projectId);
exit();
?>