<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'] ?? null;
    $stepName = $_POST['step_name'] ?? '';
    $stepDetails = $_POST['step_details'] ?? '';
    $professionalID = $_POST['professional_id'] ?? null;  // Get professional ID from form

    // Validate inputs
    if (empty($projectId) || empty($stepName) || empty($professionalID)) {
        $_SESSION['error'] = "Required fields are missing";
        header("Location: project_details.php?id=" . $projectId);
        exit();
    }

    // Temporarily disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS=0");

    // Get the next available negative stepNumber
    $stmt = $conn->prepare("SELECT MIN(stepNumber) - 1 AS next_custom_step 
                           FROM work_in 
                           WHERE projectID = ? AND stepNumber < 0");
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stepNumber = $row['next_custom_step'] ?? -1;
    $stmt->close();

    // Insert into work_in table with negative stepNumber
    $stmt = $conn->prepare("INSERT INTO work_in 
                          (professionalID, projectID, stepNumber, stepStatus, paymentStatus, stepName, stepDetails, stepType) 
                          VALUES (?, ?, ?, 'pending', 'unpaid', ?, ? , 'custom')");
    $stmt->bind_param("iiiss", $professionalID, $projectId, $stepNumber, $stepName, $stepDetails);

    if ($stmt->execute()) {
        // Update professional's availability
        $updateSql = "UPDATE professional_details SET availibilityStatus = 'Not Available' WHERE professionalID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $professionalID);
        $updateStmt->execute();
        $updateStmt->close();
        
        $_SESSION['success'] = "Custom step added successfully";
    } else {
        $_SESSION['error'] = "Error adding custom step: " . $conn->error;
    }
    
    $stmt->close();
    
    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
    
    header("Location: project_details.php?id=" . $projectId);
    exit();
} else {
    header("Location: project_details.php");
    exit();
}
?>