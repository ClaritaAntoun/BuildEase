<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

if (isset($_GET['id'])) {
    $projectId = $_GET['id'];
    $contractorId = $_SESSION['contractor_identity']['id'];
    
    // Verify the project belongs to this contractor
    $verify_sql = "SELECT projectID FROM project WHERE projectID = ? AND contractorID = ?";
    $stmt = $conn->prepare($verify_sql);
    $stmt->bind_param("ii", $projectId, $contractorId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update project status to 'rejected'
        $update_sql = "UPDATE project SET status = 'rejected' WHERE projectID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
    }
    
    $stmt->close();
}

header("Location: contractorPage.php");
exit();
?>