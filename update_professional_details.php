<?php
session_start();
include "conx.php";

if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}
$professionalId = $_SESSION['professional_identity']['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $professionalId) {
    
    $price = $_POST['price'];
    $priceDetails = $_POST['priceDetails'];
    $availibilityStatus = $_POST['availibilityStatus'];

  
    $validStatuses = ['Available', 'Busy', 'Not Available'];
    if (!in_array($availibilityStatus, $validStatuses)) {
        $_SESSION['error'] = "Invalid availability status.";
        header("Location: professionalPage.php");
        exit;
    }


    $update = $conn->prepare("UPDATE professional_details 
                              SET price=?, priceDetails=?, availibilityStatus=? 
                              WHERE professionalID=?");

    $update->bind_param("dssi", $price, $priceDetails, $availibilityStatus, $professionalId);

    $update->bind_param("dssi", $price, $priceDetails, $availibilityStatus, $professionalId);

    $update->execute();

    header("Location: professionalPage.php"); // adjust destination as needed
    exit;
} else {
    $_SESSION['error'] = "Invalid request or not logged in.";
    header("Location: professionalPage.php");
    exit;
}
