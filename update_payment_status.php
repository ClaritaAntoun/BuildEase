<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'] ?? null;
    $stepNumber = $_POST['step_number'] ?? null;
    $professionalId = $_POST['professional_id'] ?? null;
    $paymentStatus = $_POST['payment_status'] ?? null;
    
    if ($projectId && $stepNumber && $professionalId && $paymentStatus) {
        // Check current payment status first
        $checkSql = "SELECT paymentStatus FROM work_in 
                    WHERE projectID = ? AND stepNumber = ? AND professionalID = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("iii", $projectId, $stepNumber, $professionalId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && $result['paymentStatus'] !== 'paid') {
            // Only allow update if current status is not 'paid'
            $updateSql = "UPDATE work_in SET paymentStatus = ? 
                         WHERE projectID = ? AND stepNumber = ? AND professionalID = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("siii", $paymentStatus, $projectId, $stepNumber, $professionalId);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Payment status updated successfully!";
            } else {
                $_SESSION['error_message'] = "Error updating payment status.";
            }
        } else {
            $_SESSION['error_message'] = "Cannot change payment status once marked as paid.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid parameters.";
    }
    
    header("Location: step_details.php?step=$stepNumber");
    exit();
}
?>