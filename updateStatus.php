
<!-- Clarita Antoun -->
<?php
session_start();
include "conx.php";

if (!isset($_SESSION['admin_identity'])) {
    header("Location: logInPage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['homeOwnerID']) && isset($_POST['status'])) {
        $homeOwnerID = $_POST['homeOwnerID'];
        $status = $_POST['status'];

       
            $stmt = $conn->prepare("UPDATE homeowner SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $homeOwnerID);
        

        if ($stmt->execute()) {
            header("Location: adminPage.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    elseif (isset($_POST['professionalID']) && isset($_POST['status'])) {
        $professionalID = $_POST['professionalID'];
        $status = $_POST['status'];


            $stmt = $conn->prepare("UPDATE professional SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $professionalID);
    

        if ($stmt->execute()) {
            header("Location: adminPage.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    elseif (isset($_POST['contractorID']) && isset($_POST['status'])) {
        $contractorID = $_POST['contractorID'];
        $status = $_POST['status'];

    
            $stmt = $conn->prepare("UPDATE contractor SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $contractorID);
        

        if ($stmt->execute()) {
            header("Location: adminPage.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    else {
        echo "Invalid request.";
    }
}
?>
