<!-- Clarita Antoun -->
<?php
session_start(); 
include 'conx.php';

if (!isset($_SESSION['contractor_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

$contractorID = $_SESSION['contractor_identity']['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['projectID'], $_POST['action'])) {
    $projectID = intval($_POST['projectID']);
    $action = $_POST['action'];

 


    $newStatus = ($action === 'accept') ? 'active' : 'rejected';

   
    $stmt = $conn->prepare("UPDATE project SET status = ? WHERE projectID = ? AND contractorID = ?");
    $stmt->bind_param("sii", $newStatus, $projectID, $contractorID);

    if ($stmt->execute()) {
        header("Location: contractorPage.php?response=success");
        exit();
    } else {
        echo "Error updating project status.";
    }
} else {
    echo "Invalid request.";
}
?>
?>