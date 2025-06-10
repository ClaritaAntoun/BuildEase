
<!-- Clarita Antoun -->
<?php
session_start(); 
include 'conx.php';
if (!isset($_SESSION['admin_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST'){

if (isset($_POST['contractorID'], $_POST['salary'], $_POST['startDate'], $_POST['endDate'], $_POST['status'], $_POST['details'])) {
    
    $contractorID = $_POST['contractorID'];
    $salary = $_POST['salary'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $status = $_POST['status'];
    $details = $_POST['details'];


        $sql = "INSERT INTO contract (salary, startDate, endDate, status, details, contractorID) 
                VALUES ( '$salary', '$startDate', '$endDate', '$status', '$details', '$contractorID')";

        if (mysqli_query($conn, $sql)) {
            echo "Contract saved successfully.";
            header('Location:adminPage.php');
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
            header('Location:enterContract.php');
            exit;
        }

    } else {
        echo "Contractor not logged in.";
        header('Location:adminPage.php');
        exit;
    }

} else {
    echo "Missing contract data.";
    header('Location:enterContract.php');
    exit;
}



?>